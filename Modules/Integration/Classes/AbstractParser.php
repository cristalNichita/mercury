<?php


namespace Modules\Integration\Classes;


use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Integration\Entities\IntegrationLog;
use Modules\Integration\Helpers\ArrHelper;
use Modules\Integration\Interfaces\IntegrationFileInterface;

abstract class AbstractParser
{

    protected $logClass = IntegrationLog::class;
    protected $filesystem_local_path_key;
    protected $config;
    protected $dirs;
    protected $currentDir;
    protected $oldDir;
    protected $currentFiles;
    protected $messagesByLogs = [];
    protected $currentLog;
    protected $fileHelper;
    protected $full = false;

    public function __construct($status = 'new', string $file = '') {

        $this->fileHelper = new Filesystem;
        $this->setConfig($status, $file);
        $this->dirs = $this->subDirs($file);
    }

    protected function subDirs(string $file = '') {

        if($file) {
            return [ $this->config['full_path'] ];
        }

        return Storage::allDirectories($this->config->get('full_path'));
    }

    public function parse() {

        foreach($this->dirs as $dir) {

            $this->oldDir = $dir;
            $this->currentDir = $this->moveDir($dir, 'process');

            $this->currentFiles = collect(Storage::allFiles($this->currentDir));
            $files = $this->currentFiles->filter(fn($value, $key) => Str::endsWith($value, '.xml'))->sort();

            foreach($files as $file) {


                $this->checkFull($file);
                $file_name = $this->fileHelper->basename($file);
                $oldDir = rtrim($this->oldDir, '/') .'/' . $file_name;
                $this->currentLog = ($this->logClass)::createLogByFile("Импорт файла", $oldDir, $file, $file_name);
                try {
                    $xml_collection = $this->parseXmlFile(simplexml_load_string(Storage::get($file)));

                    DB::transaction(function() use ($xml_collection) {
                        $this->importRows($xml_collection);
                    });

                    $this->currentLog->setStatus('success');
                    $this->currentDir = $this->moveDir($this->currentDir, 'success');

                } catch (\ErrorException | \Throwable $e) {

                    $this->currentLog->setStatus('error');
                    $this->currentLog->exception = $e->getMessage();
                    $this->messagesByLogs[] = $e->getMessage();
                    $this->messagesByLogs[] = $e->getTraceAsString();

                    $this->currentDir = $this->moveDir($this->currentDir, 'error');

                } finally {

                    $this->addLog('Папка перемещена в ' . $this->currentDir);
                    $this->currentLog->new_path = $this->currentDir . "/{$file_name}";
                    $prefix = now()->format('Y_m_d_H_i_s');
                    $name = Str::of($file)->basename('.xml');
                    $fullPath = "logs/{$prefix}_{$name}.log";
                    Storage::disk('integration')->put($fullPath, implode("\n", $this->messagesByLogs));
                    $this->currentLog->log_path = $fullPath;
                    $this->currentLog->save();

                }

            }
        }

    }

    protected function parseXmlFile(\SimpleXMLElement $file) {

        $file = json_encode($file, 1);
        $file = json_decode($file, 1);

        return ArrHelper::recursivelyCollect($file);

    }

    abstract protected function importRows(Collection $file);
    abstract protected function importRow(array $data);

    protected function addLog(string $log): void
    {
        $this->messagesByLogs[] = $log;
    }

    public function addRowsToLog(string $mess = '', array $array = []) {

        $this->addLog("{$mess} " . count($array) .': ' . implode(' ', $array));
    }

    protected function checkFull(string $file_name)
    {
        $basename = $this->fileHelper->basename($file_name);
        $this->full = Str::contains($basename, 'full');
    }

    protected function setConfig(string $status, string $file = '') {

        $config = [];

        $config['path'] = config("filesystems.local-paths.{$this->filesystem_local_path_key}");
        $config['processPath'] = rtrim($config['path'], '/') . "/process/";
        $config['successPath'] = rtrim($config['path'], '/') . "/success/";
        $config['errorPath'] = rtrim($config['path'], '/') . "/error/";

        if($file) {
            $check = Storage::exists($file);
            if(!$check) {
                throw new \ErrorException('Файла не существует');
            }
            $config['full_path'] =  $this->fileHelper->dirname($file);

        } else {
            $config['full_path'] = rtrim($config['path'], '/') . "/{$status}";
        }

        $this->config = collect($config);

    }

    protected function moveDir(string $dir, string $type) {

        $dirs = explode('/', $dir);
        $lastDir = Arr::last($dirs);
        $newPath = $this->config["{$type}Path"] . $lastDir;

        Storage::move($dir, $newPath);

        return $newPath;

    }

    protected function updateFiles(IntegrationFileInterface $model, string $modelKey, array $extensions = ['.doc', '.docx']) {

        $files = $model->integration_files->keyBy('file_name');
        $new_files = $this->getFiles($modelKey, $extensions);
        $new_media_collection = [];

        foreach($new_files as $file) {
            $size = Storage::size($file);
            $name = $this->fileHelper->basename($file);
            $old_file = $files->get($name);

            if($old_file && $old_file->size !== $size) {
                $model->deleteMedia($old_file);
                $model->addMedia('storage/app/' . $file)->toMediaCollection($model->integration_gallery_key);

            } elseif(!$old_file) {
                $model->addMedia('storage/app/' . $file)->toMediaCollection($model->integration_gallery_key);
            }

            $new_media_collection[] = $name;
        }

        $deleted = $model->integration_files->whereNotIn('file_name', $new_media_collection);

        $deleted->each(fn($item) => $item->delete());

    }


    protected function getFiles(string $modelKey, array $extensions = ['.doc', '.docx'])
    {

        $filterFiles = $this->currentFiles->filter(function($val, $key) use($modelKey){

            $is_code = Str::of($val)->contains($modelKey);
            $is_ext  = Str::of($val)->endsWith(['.doc', '.docx']);

            return $is_code && $is_ext;

        });

        return $filterFiles->sort();
    }



}
