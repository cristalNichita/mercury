<?php

namespace Modules\Order\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Modules\Order\Entities\Payment;

class GetUnitellerPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'uniteller:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Request in Uniteller';

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
        try {

            (new Payment)->getInfoUniteller();
            $this->info('Данные успешно синхронизированы');

        } catch(\Exception $e) {
            Log::error($e->getMessage());
            $this->info("Произошла ошибка\n");
            $this->info($e->getMessage());
        }
    }
}
