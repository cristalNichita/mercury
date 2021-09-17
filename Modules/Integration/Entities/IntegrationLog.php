<?php

namespace Modules\Integration\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IntegrationLog extends Model
{
    use HasFactory;

    protected $table = 'integration_logs';
    protected $fillable = ['title', 'status', 'exception', 'log_path', 'original_path', 'new_path', 'original_name', 'new_name'];

    const STATUS_ERROR = 'error';
    const STATUS_PROCESS = 'process';
    const STATUS_SUCCESS = 'success';

    const STATUSES = [
        self::STATUS_ERROR => 'ошибка',
        self::STATUS_SUCCESS => 'успех',
        self::STATUS_PROCESS => 'в работе',
    ];

    protected $attributes = [
        'status' => self::STATUS_PROCESS
    ];

    public function getStatusTranslateAttribute()
    {
        return self::STATUSES[ $this->status ];
    }

    public function setStatus($status) {

        if(isset(self::STATUSES[$status])) {
            $this->status = $status;
        }
    }

    public static function createLogByFile($title = 'Импорт файла', $originalPath, $newPath, $file_name) {

        return self::create([
            'title' => $title,
            'original_path' => $originalPath,
            'new_path' => $newPath,
            'original_name' => $file_name
        ]);

    }
}
