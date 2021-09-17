<?php

namespace Modules\Order\Console;


use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Modules\Order\Entities\Payment;

class CheckUnitellerPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'uniteller:check';

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

            $payments = Payment::where('created_at', '<=', Carbon::now()->subDays(2))->where('status', '=', 'waiting')->get();
            foreach ($payments as $payment) {
                $payment->status = 'fail';
                $payment->save();
            }

        } catch(\Exception $e) {
            Log::error($e->getMessage());
            $this->info("Произошла ошибка\n");
            $this->info($e->getMessage());
        }
    }
}
