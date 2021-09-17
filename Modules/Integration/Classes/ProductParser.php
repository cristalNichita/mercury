<?php


namespace Modules\Integration\Classes;


use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Integration\Entities\Category;
use Modules\Integration\Entities\ParameterValue;
use Modules\Integration\Entities\Product;
use Modules\Integration\Entities\ProductParameter;
use Modules\Integration\Helpers\CreatedRow;
use Modules\Integration\Helpers\ExistRow;
use Modules\Integration\Helpers\UpdateRow;

class ProductParser extends AbstractParser
{

    protected $filesystem_local_path_key = 'products';
    protected $categories = [];
    protected $extensions = ['.jpg', '.png', '.gif', '.jpeg', '.webp'];

    public function __construct($status = 'new', string $file = '')
    {
        $this->categories = collect([]);

        parent::__construct($status, $file);
    }


    protected function importRow(array $data)
    {

        $result = null;

        $prepare_data = Product::prepareData($data);

        $product = Product::findByCode( $prepare_data[Product::integrationKey()] ) ?? new Product();

        $product->fill($prepare_data);
        if($product->isDirty()) {

            $product->save();

            if($product->wasRecentlyCreated) {
                $result = new CreatedRow($product);
            } else {
                $result = new UpdateRow($product);
            }

        } else {
            $result = new ExistRow($product);
        }

        if(!empty($data['РодительКод'])) {

            $codes = $data['РодительКод'];

            if(is_string($codes)) {
                $codes = [ $codes ];
            }

            $categories = [];

            foreach($codes as $code) {

                if($cat = $this->categories->get($code)) {

                    $categories[] = $cat->id;

                }
            }

            $product->categories()->sync($categories);

        }

        return $result;
    }

    protected function importParameters(array $data, $product) {


        if( ($data['ТоварДопСвойства'])->isNotEmpty() ) {

            $product_props = $data['ТоварДопСвойства'];
            $product_props = $product_props->first();

            if(isset($product_props['Имя'])) {

                $product_props = [ $product_props ];
            }



            if($product_props) {

                $valuesIds = [];
                foreach($product_props as $prop) {
                    $updatedProp = ProductParameter::fillFromParser($prop);
                    $value = ParameterValue::fillFromParser($prop, $updatedProp->id);
                    $valuesIds[] = $value->id;
                }
                $product->parameters_values()->sync($valuesIds);
            }
        }


    }

    protected function importRowsProducts(Collection $file) {

        $products = $file->get('Номенклатура', new Collection([]));
        $products = $products->get('Товар');

        if(!$products) {
            $this->addLog('Продуктов для парсинга не обнаружено');
            return;
        }

        $created = [];
        $updated = [];
        $exist = [];
        $deleted = [];


        foreach($products as $data) {

            $result = $this->importRow($data);
            $product = $result->model;

            if($result instanceof ExistRow) {
                $exist[] = $product->integration_code;
            }
            elseif($result instanceof UpdateRow) {

                $updated[] = $product->integration_code;
            }
            elseif($result instanceof CreatedRow) {
                $created[] = $product->integration_code;
            }

            $this->updateFiles($product, $product->integration_code, $this->extensions);
            $this->importParameters($data, $product);
        }

        $this->addRowsToLog('Создано продуктов', $created);
        $this->addRowsToLog('Обновлено продуктов', $updated);
        $this->addRowsToLog('Продуктов без изменений', $exist);
        $this->addRowsToLog('Удалено продуктов', $deleted);


    }

    protected function importRows(Collection $file)
    {
        $this->importRowsCategories($file);
        $this->importRowsProducts($file);
    }

    protected function importRowsCategories(Collection $file) {

        $this->addLog('Начало парсинга категорий');
        $this->categories = Category::all()->keyBy('id_1c');
        $new_categories = $file->get('ГруппыНоменклатуры');
        if($new_categories) {
            $new_categories = $new_categories->get('Группа');
        }

        if(!$new_categories) {
            $this->addLog('Категорий для парсинга не обнаружено');
            return;
        }

        $new_categories = $new_categories->keyBy('Код');
        $created = [];
        $updated = [];
        $exist = [];
        $deleted = [];
        $codes = $new_categories->keys()->sort();

        foreach($codes as $code) {

            $data = $new_categories[$code];
            $result = $this->importRowCategory($code, $data, $this->categories);

            if($result instanceof ExistRow) {
                $exist[] = $code;
            }
            elseif($result instanceof UpdateRow) {

                $updated[] = $code;
            }
            elseif($result instanceof CreatedRow) {
                $created[] = $code;
            }

            $this->categories[$code] = $result->model;
        }

        $merge = array_merge($created, $updated, $exist);
        $flip = array_flip($merge);

        foreach($this->categories as $code => $cat) {

            if(!isset($flip[$code])) {

                $deleted[] = $code;
                $this->categories->pull($code);
                $cat->delete();
            }

        }

        $this->addRowsToLog('Создано категорий', $created);
        $this->addRowsToLog('Обновлено категорий', $updated);
        $this->addRowsToLog('Категорий без изменений', $exist);
        $this->addRowsToLog('Удалено категорий', $deleted);

    }

    protected function importRowCategory($code, $data, Collection $categories) {


        $data = Category::prepareData($data, $categories);
        $exist_cat = $categories->get($code);

        if( !$exist_cat ) {
            $category = Category::create($data);
            return new CreatedRow($category);
        }

        $exist_cat->fill($data);

        if($exist_cat->isDirty()) {
            $exist_cat->save();
            return new UpdateRow($exist_cat);
        }

        return new ExistRow($exist_cat);
    }
}
