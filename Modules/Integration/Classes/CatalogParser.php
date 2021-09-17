<?php


namespace Modules\Integration\Classes;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Catalog\Entities\Category;
use Modules\Catalog\Entities\ParameterValue;
use Modules\Catalog\Entities\Product;
use Modules\Catalog\Entities\ProductParameter;

class CatalogParser extends BaseParser
{
    protected $categories;
    protected $products;


    public function import($xml)
    {
        $this->importCategories($xml);
        $this->importProducts($xml);

        // Пересчитваем кол-во товаров категории
        Category::recalculate();
    }

    public function prepareProducts(\SimpleXMLElement $products)
    {
        $result = [];
        foreach ($products as $rawData) {
            $product = [
                'id_1c' => trim((string)$rawData['Код']),
                'title' => trim((string)$rawData['Наименование']),
                'description' => trim((string)$rawData['Описание']),
                'price' => (double)$rawData['Цена'],
                'quantity' => (int)$rawData['Остатки'],
                'quantity_main' => 0,
                'quantity_remote' => 0,
                'weight' => (double)$rawData['Вес'],
                'volume' => (double)$rawData['Объем'],
                'deleted' => ((string)$rawData['ПометкаУдаления'] === 'Да'),
                'category' => null,
            ];

            foreach ($rawData->ОстаткиНаСкладах->Склад as $sklad) {
                if ((string)$sklad['GUID'] === '125fa0b6-8d3d-11e1-8c52-001e5848397d') {
                    $product['quantity_main'] = (int)$sklad['Остаток'];
                } else {
                    $product['quantity_remote'] = (int)$sklad['Остаток'];
                }
            }

            $parent = (string)$rawData['РодительКод'];
            if (!empty($parent) && !empty($this->categories[$parent])) {
                $product['category'] = $this->categories[$parent]->id;
            }

            $params = [];

            // Бренд
            $brend = trim((string)$rawData['Производитель']);
            if (!empty($brend)) {
                $params['Производитель'] = [
                    'id_1c' => 'Производитель',
                    'title' => 'Производитель',
                    'value' => $brend
                ];

            }
            //Доп. параметры
            foreach ($rawData->ТоварДопСвойства->ДопСвойства as $rawParam) {
                $params[trim((string)$rawParam['Свойство'])] = [
                    'id_1c' => (string)$rawParam['Имя'],
                    'title' => trim((string)$rawParam['Свойство']),
                    'value' => trim((string)$rawParam['ЗначениеСвойства'])
                ];
            }

//            $analogs = [];
//
//            foreach ($rawData->Аналоги->Аналог as $rawAnalog) {
//                $analogs[trim((string)$rawAnalog['НоменклатураГУИД'])] = [
//                    'id_1c' => (string)$rawAnalog['АналогГУИД'],
//                    'title' => trim((string)$rawAnalog['Аналог']),
//                    'sort' => trim((string)$rawAnalog['Приоритет'])
//                ];
//            }

            $product['params'] = $params;

//            $product['analogs'] = $analogs;

            $product['images'] = $this->getFiles($product['id_1c']);

            $result[$product['id_1c']] = $product;
        }

        return $result;
    }

    public function prepareCategories(\SimpleXMLElement $groups)
    {
        $result = [];
        foreach ($groups as $rawData) {
            $group = [];
            $group['title'] = (string)$rawData['Наименование'];
            $group['id_1c'] = (string)$rawData['Код'];
            $group['parent_id'] = (string)$rawData['Родитель'];
            $group['active'] = true;

            $result[$group['id_1c']] = $group;
        }

        return $this->sortCategories($result);
    }

    protected function importCategories(\SimpleXMLElement $xml)
    {

        $this->categories = Category::all()->keyBy('id_1c');
        $groups = $this->prepareCategories($xml->ГруппыНоменклатуры->Группа);

        $deleted = clone $this->categories;

        // Чтобы все категории были созданы и не было несуществующиз parent_id
        foreach ($groups as $code => $group) {
            if (!isset($this->categories[$code])) {
                unset($group['parent_id']);
                $this->categories[$code] = Category::create($group);
            }
        }

        // Обновляем
        foreach ($groups as $code => $group) {
            if (!empty($group['parent_id'])) {
                $group['parent_id'] = $this->categories[$group['parent_id']]->id;
            } else {
                $group['parent_id'] = 0;
            }
            $this->categories[$code]->update($group);
            unset($deleted[$code]);
        }

        // Категории отсутвующие в 1С удаляем
        Category::whereIn('id', $deleted->keyBy('id')->keys())->update(['active' => false]);
    }

    protected function importProducts(\SimpleXMLElement $xml)
    {
        $deleted_products = Product::select(['id', 'id_1c'])->get()->keyBy('id_1c');

        $products = $this->prepareProducts($xml->Номенклатура->Товар);

        $product_params = ProductParameter::all()->keyBy('title');

        $forceDelete = [];

        $disk = Storage::disk('integration');

        foreach ($products as $code => $raw_product) {

            if ($raw_product['deleted']) {
                $forceDelete[$code] = $code;
                continue;
            }

            $product = Product::firstOrNew(['id_1c' => $code]);
            $product->fill($raw_product);
            $product->save();

            $product->categories()->sync($raw_product['category']);

            $params_values = [];

            foreach ($raw_product['params'] as $param_code => $raw_param) {

                if (!isset($product_params[$param_code])) {
                    $product_params[$param_code] = ProductParameter::create($raw_param);
                }

                $value = ParameterValue::firstOrCreate([
                    'value' => $raw_param['value'],
                    'product_parameter_id' => $product_params[$param_code]->id
                ]);
                $params_values[] = $value->id;
            }

            $product->parameters_values()->sync($params_values);

            foreach ($raw_product['images'] as $new_image) {
                $product->addOrUpdateImage($disk->path($new_image));

                $success = str_replace('/ready/', '/success/', $new_image);
                if ($disk->exists($success)) {
                    $disk->delete($success);
                }
                $disk->move($new_image, $success);
            }

//            foreach ($raw_product['analogs'] as $product_guid => $raw_analog) {
//
//            }

            unset($deleted_products[$code]);
        }

        if (!empty($forceDelete)) {
            Product::whereIn('id_1c', $forceDelete)->delete();
        }

        if ($this->is_full && (!empty($deleted_products))) {
            Product::whereIn('id_1c', $deleted_products->keys())->delete();
        }
    }

    protected function getFiles(string $name, array $extensions = ['.doc', '.docx'])
    {
        $files = Storage::disk('integration')->files($this->work_dir);
        $files = collect($files)->filter(function ($val, $key) use ($name) {
            return Str::of(basename($val))->contains($name);
        })->sort();
        return $files;
    }

    protected function sortCategories (array $categories)
    {
        $collect_categories = collect($categories);
        $sort_categories = $collect_categories->sortBy('parent_id', SORT_NATURAL)
                                               ->where('parent_id', '');


        $parent_ids = $sort_categories->keys();
        while ($sort_categories->count() !== $collect_categories->count()) {
            $sort_childs = $collect_categories->whereIn('parent_id', $parent_ids)
                                              ->sortBy('parent_id', SORT_NATURAL);

            $parent_ids = $sort_childs->keys();
            $sort_categories = $sort_categories->merge($sort_childs);
        }

        return $sort_categories->toArray();
    }

}
