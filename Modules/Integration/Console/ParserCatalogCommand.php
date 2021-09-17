<?php

namespace Modules\Integration\Console;

use Illuminate\Console\Command;
use Modules\Integration\Classes\CatalogParser;
use Modules\Integration\Traits\ParserCommandTrait;

/**
 * Class ParserCatalogCommand
 * Импорт каталога товаров
 * @package Modules\Integration\Console
 */
class ParserCatalogCommand extends Command
{

    use ParserCommandTrait;

    protected $signature = 'parser:catalog';
    protected $description = 'Парсер каталога товаров';

    // Каталог откуда забирать xml
    protected $folder = 'products';

    public function handle()
    {
        $this->info($this->description);
        $this->info('--------------------------------');
        $this->newLine();

        if (!$this->moveToProcessFolder()) {
            return;
        }

        $parser = new CatalogParser();

        $xml_files = $this->getXmlFiles();

        foreach ($xml_files as $xml_file) {

            try {
                $this->line('Обработка: ' . $xml_file);
                $parser->process($xml_file);
                $this->moveToSuccess($xml_file);
            } catch (\Throwable $e) {
                $this->error($e->getMessage());
                $this->moveToError($xml_file);
            }
        }
    }
}
