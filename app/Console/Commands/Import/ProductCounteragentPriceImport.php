<?php

namespace App\Console\Commands\Import;

use App\Models\Product;
use App\Models\ProductCounteragentPrice;
use App\Models\Role;
use Illuminate\Console\Command;

class ProductCounteragentPriceImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:productCounteragentPrice';

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
        foreach (\DB::connection('boszhan')->table('product_counteragent_prices')->get() as $price) {
            ProductCounteragentPrice::updateOrCreate(
                ['id' => $price->id],
                [
                    'id' => $price->id,
                    'product_id' => $price->product_id,
                    'counteragent_id' => $price->counteragent_id,
                    'price' => $price->price,
                ]
        );
        }
    }
}
