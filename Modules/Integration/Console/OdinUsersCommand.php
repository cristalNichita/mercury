<?php

namespace Modules\Integration\Console;

use Illuminate\Console\Command;
use Modules\Integration\Services\OdinService;
use Modules\Integration\Traits\ParserCommandTrait;
use Modules\User\Entities\Holding;

/**
 * Class OdinUsersCommand
 * Экспорт контрагентов (компаний) и контактных лиц (контакты)
 * @package Modules\Integration\Console
 */
class OdinUsersCommand extends Command
{

    use ParserCommandTrait;

    protected $signature = 'odin:users';
    protected $description = 'Полный экспорт компаний и контактов';

    public function handle()
    {
        $this->info($this->description);
        $this->info('--------------------------------');
        $this->newLine();

        $xml = OdinService::createXml('ДанныеОКлиенте');

        $holdings = Holding::with(['contacts.params', 'companies.params'])->get();
        foreach ($holdings as $holding) {
            $this->line($holding->name);
            OdinService::addHolding($xml, $holding);
        }

        OdinService::save($xml, 'users', 'full');

        $this->info('Экспорт успешно завершен');
    }
}
