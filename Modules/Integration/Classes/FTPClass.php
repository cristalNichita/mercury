<?php


namespace Modules\Integration\Classes;


use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Modules\Integration\Entities\IntegrationLog;


/**
 * Класс для закачки файлов на локальный диск
 * Class FTPClass
 * @package Modules\Integration\Classes
 */
class FTPClass
{

    protected $logClass = IntegrationLog::class;
    protected $ftp_dirs;
    protected $local_dirs;
    protected $file_helper;
    protected $prefix;


    public function __construct() {

        $this->ftp_dirs = config('filesystems.ftp-paths');
        $this->local_dirs = config('filesystems.local-paths');
        $this->file_helper = new Filesystem;
        $this->prefix = now()->format('Y_m_d_H_i_s');
    }

    /**
     * todo: Добавить логи
     */
    public function downloadFiles() {


        foreach($this->ftp_dirs as $key => $dir) {

            $process_path = $this->local_dirs[$key] . "/process/{$this->prefix}/";
            $new_path = $this->local_dirs[$key] . "/new/{$this->prefix}/";
            $ftpLog = new $this->logClass;
            $ftpLog->title = 'Скачивание файлов';
            $ftpLog->original_path = $dir;
            $ftpLog->new_path = $process_path;
            $ftpLog->setStatus('process');
            $ftpLog->save();


            try {
                $files = Storage::disk('ftp')->allFiles($dir);
                foreach($files as $file) {
                    $this->downloadFile($file, $new_path, $process_path);
                    Storage::disk('ftp')->delete($file);
                }

                if(!Storage::allFiles($process_path)) {
                    Storage::deleteDirectory($process_path);
                }

                $ftpLog->setStatus('success');
                $ftpLog->new_path = $new_path;
                $ftpLog->save();

            } catch( \ErrorException | \Throwable $e) {
                $ftpLog->setStatus('error');
                $ftpLog->exception = $e->getMessage();
                $ftpLog->save();
            }


        }



    }


    protected function downloadFile($file, $new_path, $process_path) {

        $file_name = $this->file_helper->basename($file);
        $logFile = new $this->logClass;
        $logFile->title  = 'Скачивание файла';
        $logFile->original_path = $file;
        $logFile->new_path = $process_path;
        $logFile->original_name = $file_name;
        $logFile->new_name = $file_name;
        $logFile->save();

        try {

            $download_file = Storage::disk('ftp')->get($file);
            $result = Storage::put($process_path . '/' . $file_name, $download_file);

            if(!$result) {
                throw new \ErrorException('не удалось сохранить файл');
            }

            $result_move = Storage::move($process_path . '/' . $file_name, $new_path . '/' . $file_name);

            if(!$result_move) {
                throw new \ErrorException('не удалось переместить файл');
            }

            $logFile->setStatus('success');
            $logFile->new_path = $new_path;
            $logFile->save();

        }  catch( \ErrorException | \Throwable $e) {

            $logFile->setStatus('error');
            $logFile->exception = $e->getMessage();
            $logFile->save();

        }

    }


}
