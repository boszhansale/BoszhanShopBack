<?php

namespace App\Console\Commands\Import;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class RunImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:run';

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
        Artisan::call('import:brand');
        Artisan::call('import:category');
        Artisan::call('import:priceType');
        Artisan::call('import:role');
        Artisan::call('import:reasonRefund');

        Artisan::call('import:counteragent');
        Artisan::call('import:product');
        Artisan::call('import:productBarcode');
        Artisan::call('import:productImage');
        Artisan::call('import:productCounteragentPrice');
        Artisan::call('import:productPriceType');

        Artisan::call('import:user');

    }
}
