<?php

namespace App\Console\Commands\Import;

use App\Models\ReasonRefund;
use App\Models\Role;
use Illuminate\Console\Command;

class ReasonRefundImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:reasonRefund';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        foreach (\DB::connection('boszhan')->table('reason_refunds')->get() as $reasonRefund) {
            ReasonRefund::updateOrCreate(
                ['id' => $reasonRefund->id],
                [
                    'id' => $reasonRefund->id,
                    'type' => $reasonRefund->type,
                    'title' => $reasonRefund->title,
                    'code' => $reasonRefund->code,
                ]
            );
        }
    }
}
