<?php

namespace Modules\Integration\Console;

use Illuminate\Console\Command;
use Modules\Integration\Classes\UsersParser;
use Modules\Integration\Traits\ParserCommandTrait;

/**
 * Class ParserCatalogCommand
 * Импорт контрагентов (компаний) и контактных лиц (контакты)
 * @package Modules\Integration\Console
 */
class ParserUsersCommand extends Command
{

    use ParserCommandTrait;

    protected $signature = 'parser:users';
    protected $description = 'Парсер компаний и контактов';

    public function handle()
    {
        $this->info($this->description);
        $this->info('--------------------------------');
        $this->newLine();

        $this->folder = 'users';

        if (!$this->moveToProcessFolder()) {
            return;
        }

        $parser = new UsersParser();

        $xml_files = $this->getXmlFiles();

        foreach ($xml_files as $xml_file) {

            try {

                $this->line('Обработка: ' . $xml_file);

                $parser->process($xml_file);

                $this->moveToSuccess($xml_file);

            } catch (\Throwable $e) {
                $this->error($e->getMessage());
                $this->line($e->getTraceAsString());
                $this->moveToError($xml_file);
            }
        }
    }
}
