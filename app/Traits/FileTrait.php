<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

trait FileTrait
{
    /**
     * @param $file
     * @return array
     */
    public function fileUpload($file)
    {
        /** @var $file UploadedFile */
        $fileExt = $file->getClientOriginalExtension();
        $fileOriginName = $file->getClientOriginalName();
        $fileName = Str::slug(time().'_'. Str::random(20)) . '.' . $fileExt;
        $filePath = $file->storeAs('upload_files',$fileName,'public');

        return [
            'fileOrigin' => $fileOriginName,
            'fileName'   => $fileName,
            'filePath'   => $filePath
        ];
    }
}
