<?php

namespace Modules\Settings\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GlobalDirectory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code'];

    public $timestamps = false;

    public function items()
    {
        return $this->hasMany(GlobalDirectoryItem::class, 'directory_id');
    }
}
