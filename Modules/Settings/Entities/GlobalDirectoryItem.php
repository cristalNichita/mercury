<?php

namespace Modules\Settings\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GlobalDirectoryItem extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'additions', 'directory_id'];

    public $timestamps = false;

    public function directory()
    {
        return $this->belongsTo(GlobalDirectory::class, 'directory_id');
    }
}
