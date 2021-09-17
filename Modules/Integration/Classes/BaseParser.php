<?php


namespace Modules\Integration\Classes;


use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BaseParser
{
    protected $work_dir;
    protected $is_full = false;

    public function process($xml_file)
    {
        $this->work_dir = dirname($xml_file);
        $this->is_full = Str::contains(basename($xml_file), 'full');

        $xml = simplexml_load_string(Storage::disk('integration')->get($xml_file));

        $this->import($xml);
    }
}
