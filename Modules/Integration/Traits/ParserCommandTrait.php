<?php


namespace Modules\Integration\Traits;


use Illuminate\Support\Facades\Storage;

trait ParserCommandTrait
{

    protected $disk_1c;
    protected $disk_integration;
    protected $folder = 'products';

    public function __construct()
    {
        parent::__construct();

        $this->disk_1c = Storage::disk('1c');
        $this->disk_integration = Storage::disk('integration');
    }

    public function moveToProcessFolder(): int
    {

        $files = $this->disk_1c->files("$this->folder/ready");

        if (empty($files)) {
            $this->line('Нет файлов для импорта');
            return 0;
        }

        foreach ($files as $file) {
            //Пока просто копирование
            $old_file = $this->disk_1c->path($file);
            $new_file = $this->disk_integration->path($file);
            rename($old_file, $new_file);
        }

        $this->info('Перенесено ' . count($files) . ' файлов');
        $this->newLine();

        return count($files);
    }

    public function getXmlFiles()
    {
        $files = $this->disk_integration->files("$this->folder/ready");
        return preg_grep('/\.xml$/', $files);
    }

    public function moveToSuccess($xml_file)
    {
        $success = str_replace('/ready/', '/success/', $xml_file);
        if ($this->disk_integration->exists($success)) {
            $this->disk_integration->delete($success);
        }
        $this->disk_integration->move($xml_file, $success);
    }

    public function moveToError($xml_file)
    {
        $success = str_replace('/ready/', '/error/', $xml_file);
        if ($this->disk_integration->exists($success)) {
            $this->disk_integration->delete($success);
        }
        $this->disk_integration->move($xml_file, $success);
    }

}
