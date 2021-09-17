<?php

namespace Modules\Integration\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Modules\Integration\Classes\OrderParser;
use Modules\Integration\Classes\ParserController;
use Modules\Integration\Classes\ProductParser;
use Modules\Integration\Classes\UserParser;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RunParserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parser:run {parser : Доступные парсеры: каталог (catalog), заказы (orders), пользователи (users)} {--status=new : Из какой папки брать}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Запуск парсера.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $parser = $this->getParser();
        $parser->parse();
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['status', InputArgument::REQUIRED, 'new'],
        ];
    }

    protected function getParser() {

        $parsers = [
            'users' => UserParser::class,
            'orders' => OrderParser::class,
            'catalog' => ProductParser::class
        ];

        $status_allowed = ['new', 'error', 'success'];

        $parser_key = $this->argument('parser');
        $status = $this->option('status');

        $validator = Validator::make([
            'parser_class' => $parser_key,
            'status' => $status
        ],
        [
            'parser_class' => ['required', 'string', Rule::in(array_keys($parsers))],
            'status' => ['required', 'string', Rule::in($status_allowed)],
        ],
        [
            'parser_class.in' => "Не правильное значение атрибута :attribute. Допустимые:\n" . implode(', ', array_keys($parsers)),
            'status.in' => "Не правильное значение атрибута :attribute. Допустимые:\n" . implode(', ', $status_allowed)
        ],
        [
            'parser_class' => 'Парсер',
            'status' => 'Статус'
        ]

        );

        if ($validator->fails()) {
            $messages = collect($validator->messages())->flatten()->all();
            foreach ($messages as $failure) {
                $this->error($failure);
            }
            die;
        }

        $parser_class = $parsers[ $parser_key ];
        $parser = new $parser_class( $status );

        return $parser;

    }
}
