<?php

namespace Modules\Catalog\Entities;

use App\Traits\ImagesTrait;
use App\Traits\SortableTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Str;
use Modules\Catalog\Traits\ProductFilterableTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Class Product
 * @property string $description Полное описнаиие продукта (TODO: перенести в content)
 * @property-read string $intro Аннотация (строится на основе description)
 * @property int $quantity Кол-во на складе
 * @property int $is_new Новинка
 * @property int $is_sale Акция
 * @property int $is_offer Выгодное предложение
 * @package Modules\Catalog\Entities
 */
class Product extends Model implements HasMedia
{
    use Sluggable;

    // Сортировка
    use SortableTrait;

    // Фильтрация товаров
    use ProductFilterableTrait;

    // Картинки
    use ImagesTrait;

    // Картинки нужны всегда, но не нужны в виде media
    protected $with = ['media', 'categories'];
    protected $hidden = ['media'];
    protected $appends = ['intro', 'image'];

    protected $fillable = [
        'id_1c', 'article', 'slug',
        'title', 'description', 'content',
        'price', 'old_price',
        'quantity', 'quantity_main', 'quantity_remote',
        'is_sale', 'is_offer', 'is_new',
        'rating',
        'brand_id',
        'weight',
        'volume'
    ];

    protected $casts = [
        'is_sale' => 'boolean',
        'is_offer' => 'boolean',
        'is_new' => 'boolean'
    ];

    /**
     * Связь с категориями
     * @return BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * Связь с параметрами
     * @return BelongsToMany
     */
    public function parameters(): BelongsToMany
    {
        return $this->belongsToMany(ProductParameter::class, 'product_parameters_2_products');
    }

    /**
     * Связь до значений параметров
     * @return BelongsToMany
     */
    public function parameters_values(): BelongsToMany
    {
        return $this->belongsToMany(ParameterValue::class, 'parameter_values_2_products');
    }

    /**
     * Связь с брендом
     * @return BelongsTo
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function recommendation()
    {
        return $this->hasMany(RecommendedProduct::class)
            ->withoutGoodsDay();
    }

    public function analog()
    {
        return $this->hasMany(AnalogProduct::class);
    }

    public function sluggable(): array
    {
        return ['slug' => ['source' => 'title']];
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where('slug', $value)->orWhere('id', $value)->firstOrFail();
    }

    /**
     * Главная картинка - первая из галлереи
     * @return array
     */
    public function getImageAttribute()
    {

        $image = $this->getFirstMedia('gallery');
        if (!$image) {
            return null;
        }

        $result = ['id' => $image->id, 'name' => $image->file_name];
        foreach ($this->getSizes() as $conversion => $sizes) {
            $result[$conversion] = $this->getFirstMediaUrl('gallery', $conversion);
        }

        return $result;
    }

    public function getParamsAttribute()
    {
        $param_values = $this->parameters_values;
        if (empty($param_values)) {
            return [];
        }

        $result = [];
        foreach ($param_values as $value) {
            $result[$value->product_parameter_id] = ProductParameter::find($value->product_parameter_id);
            $result[$value->product_parameter_id]->value = $value->value;
        }

        return array_values($result);
    }

    public function getIntroAttribute()
    {
        return Str::words(strip_tags($this->description), 15);
    }

    public function scopeFilterParams ($builder, array $filter) {

        foreach ($filter as $product_parameter_id => $parameter_values) {
            $builder->whereHas('parameters_values', function($builder_pv) use ($product_parameter_id, $parameter_values){
                $builder_pv->where(function($query) use ($product_parameter_id, $parameter_values) {
                    if (is_array($parameter_values)) {
                        foreach ($parameter_values as $parameter_value) {
                            $query->orWhere(function ($query) use($product_parameter_id, $parameter_value) {
                                return  $query->where('parameter_values.product_parameter_id', '=', $product_parameter_id)
                                    ->where('parameter_values.value', 'like',  $parameter_value);
                            });
                        }
                    } else {
                        $query->where(function ($query) use($product_parameter_id, $parameter_values) {
                            return $query->where('parameter_values.product_parameter_id', '=', $product_parameter_id)
                                ->where('parameter_values.value', 'like',  $parameter_values);
                        });
                    }
                });


                return $builder_pv;
            });
        }

        return $builder;

    }
}
