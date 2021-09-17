<?php

namespace Modules\User\Entities;

use App\Traits\ImagesTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;

/**
 * Class Document
 * @package Modules\User\Entities
 * @deprecated ???
 */
class Document extends Model implements HasMedia
{
    use HasFactory, ImagesTrait;

    const DOC_TYPE_1_ID = 1;
    const DOC_TYPE_2_ID = 2;
    const DOC_TYPE_1_TITLE = 'Договор';
    const DOC_TYPE_2_TITLE = 'Акт сверки';

    protected $fillable = ['guid', 'name', 'deleted', 'status',
        'date', 'date_start', 'date_end', 'number', 'documentable_type', 'documentable_id'];

    protected static function newFactory()
    {
        return \Modules\Integration\Database\factories\DocumentFactory::new();
    }

    public function documentable() {
        return $this->morphTo();
    }

    public function getUrl()
    {
        return $this->getMedia()->first()
            ? $this->getMedia()->first()->getUrl()
            : null;
    }

    public static function getTypeArr()
    {
        return [
            self::DOC_TYPE_1_ID => self::DOC_TYPE_1_TITLE,
            self::DOC_TYPE_2_ID => self::DOC_TYPE_2_TITLE
        ];
    }
}
