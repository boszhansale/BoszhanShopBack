<?php

namespace App\Console\Commands\Import;

use App\Models\Product;
use App\Models\ProductCounteragentPrice;
use App\Models\ProductPriceType;
use App\Models\Role;
use Illuminate\Console\Command;

class ProductPriceTypeImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:productPriceType';

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
        foreach (\DB::connection('boszhan')->table('product_price_types')->get() as $price) {
            ProductPriceType::updateOrCreate(
                ['id' => $price->id],
                [
                    'id' => $price->id,
                    'product_id' => $price->product_id,
                    'price_type_id' => $price->price_type_id,
                    'price' => $price->price,
                    'deleted_at' => $price->deleted_at,
                ]
        );
        }
    }
}
