<?php

namespace Modules\Catalog\Entities;

use App\Traits\FilterableTrait;
use App\Traits\ImagesTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;
use Kalnoy\Nestedset\NodeTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Spatie\MediaLibrary\HasMedia;

/**
 * Категория товара.
 *
 * @package Modules\Catalog\Entities
 * @property int $id
 * @property int $id_1c ID в 1С
 * @property string $title Название категории
 * @property bool $active Статус активности категории
 * @property int $product_count Колличество товаров в категории
 * @property string $slug Уникальный идентификатор понятный человеку
 * @property-read Category $parent Родительская категория
 * @property-read Collection $products Все продукты в категории
 */
class Category extends Model implements HasMedia
{
    use HasFactory, FilterableTrait, NodeTrait, Sluggable, ImagesTrait {
        NodeTrait::replicate as replicateNode;
        Sluggable::replicate as replicateSlug;
    }

    protected $hidden = ['media'];
    protected $appends = ['image'];

    protected $fillable = [
        'id_1c',
        'title', 'slug',
        'active',
        'product_count',
        'parent_id'
    ];

    protected static function newFactory()
    {
        return \Modules\Catalog\Database\factories\CategoryFactory::new();
    }

    public $attributes = [
        'product_count' => 0
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function replicate(array $except = null)
    {
        $instance = $this->replicateNode($except);
        (new SlugService())->slug($instance, true);

        return $instance;
    }

    /**
     * Продукты непосредствеенно в текуей категории
     * @return BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    /**
     * Связь с родителем
     * @return BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Пересчитать колличество товара в категории.
     * @return void
     */
    public static function recalculate()
    {
        $categories = self::all();

        foreach ($categories as $category) {
            $category->product_count = $category->products()->count();
            $category->save();
        }
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where('slug', $value)->orWhere('id', $value)->firstOrFail();
    }

    /**
     * Получить атрибуты для фильтрации товаров.
     *
     * @return array
     */
    public function getFiltersAttribute()
    {
        $result = [];

        $result[] = [
            'field' => 'price',
            'key' => rand(0, 999999),
            'title' => 'Цена',
            'type' => 'range',
            'min' => $this->products()->min('price'),
            'max' => $this->products()->max('price'),
            'dimension' => 'руб.',
        ];

        $category_id = $this->id;

        $parameter_values = ParameterValue::with('parameter')
            ->whereHas(
                'products.categories',
                function ($query) use ($category_id) {
                    $query->where('categories.id', $category_id);
                }
            )->get();

        if ($parameter_values->isEmpty()) {
            return $result;
        }

        $params = [];

        foreach ($parameter_values as $value) {
            $params[$value->product_parameter_id]['values'][] = $value->value;

            if (empty($params[$value->product_parameter_id]['object'])) {
                $params[$value->product_parameter_id]['object'] =
                    $value->parameter;
            }
        }

        foreach ($params as $parameter_id => $parameter) {
            $result[] = [
                'field' => "field_$parameter_id",
                'key' => rand(0, 999999),
                'title' => $parameter['object']->title,
                'type' => 'checkbox',
                'options' => $parameter['values'],
                'parameter' => $parameter['object'],
            ];
        }

        return $result;
    }

}
