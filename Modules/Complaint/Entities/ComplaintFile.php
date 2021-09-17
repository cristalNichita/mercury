<?php

namespace Modules\Complaint\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class ComplaintFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'file_path',
        'complaint_id'
    ];

    public $appends = ['url'];

    protected static function newFactory()
    {
        return \Modules\Complaint\Database\factories\ComplaintFileFactory::new();
    }

    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }

    public function getUrlAttribute() {
        return $this->file_path ? Storage::disk('public')->url($this->file_path) : null;
    }

    public function getFullPathAttribute() {
        return $this->file_path ? public_path($this->file_path) : null;
    }
}
