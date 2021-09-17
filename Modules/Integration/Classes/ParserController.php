<?php


namespace Modules\Integration\Classes;


use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;

class ParserController
{

    const PATH_ERROR = 'integration/error';
    const PATH_PROCESS = 'integration/process';
    const PATH_NEW = 'integration/new';

    const PATHS = [

    ];

    const PARSING_PATHS = [
        'error' => self::PATH_ERROR,
        'new'   => self::PATH_NEW,
    ];

    const PARSERS = [
        'Заказы' => ProductParser::class,
        'Номенклатура' => ProductParser::class,
        'Реализации'
    ];

    protected $parsing_path;
    protected $file_helper;

    public function __construct($parsing = 'new')
    {
        if( empty( self::PARSING_PATHS[$parsing]) ) {
            throw new \ErrorException('не правильный аргумент парсинга');
        }

        $this->parsing_path = self::PARSING_PATHS[$parsing];
        $this->file_helper = new Filesystem;
    }

    public function run() {


        dd($files, $this->file_helper);

        $files = Storage::files($this->parsing_path);

        foreach($files as $old_file_path) {

//            $new_file_path = $process_files_path . '/' . $file_helper->basename($old_file_path);
//            Storage::move($old_file_path, $new_file_path);
//            $simple_xml_file = simplexml_load_string(Storage::get($new_file_path));

            $simple_xml_file = simplexml_load_string(Storage::get($old_file_path));
            $name = $simple_xml_file->getName();

            if( !empty(self::PARSERS[ $name ]  ) ) {

//                $parser = (new (self::PARSERS[ $name ]))($simple_xml_file);
//                $parser->run();

            }

        }

    }

}
