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
        $prices = \DB::connection('boszhan')->table('product_price_types')->where('price_type_id',3)->get();
        foreach ($prices as $price) {
            if (ProductPriceType::find($price->id)){
                continue;
            }
            try {
                ProductPriceType::updateOrCreate(
                    ['id' => $price->id],
                    [
                        'id' => $price->id,
                        'product_id' => $price->product_id,
                        'price_type_id' => $price->price_type_id,
                        'price' => $price->price,
                    ]
                );
            }catch (\Exception $e){
                dd($price);
            }
        }
    }
}
