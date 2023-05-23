<?php

namespace App\Console\Commands\Import;

use App\Models\Product;
use App\Models\Role;
use Illuminate\Console\Command;

class ProductImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:product';

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
        foreach (\DB::connection('boszhan')->table('products')->get() as $product) {
            Product::updateOrCreate(
                ['id' => $product->id],
                [
                    'id' => $product->id,
                    'name' => $product->name,
                    'category_id' => $product->category_id,
                    'id_1c' => $product->id_1c,
                    'article' => $product->article,
                    'measure' => $product->measure,
                    'barcode' => $product->barcode,
                    'remainder' => $product->remainder,
                    'enabled' => $product->enabled,
                    'purchase' => $product->purchase,
                    'return' => $product->return,
                    'presale_id' => $product->presale_id,
                    'discount' => $product->discount,
                    'hit' => $product->hit,
                    'new' => $product->new,
                    'action' => $product->action,
                    'discount_5' => $product->discount_5,
                    'discount_10' => $product->discount_10,
                    'discount_15' => $product->discount_15,
                    'discount_20' => $product->discount_20,
                    'rating' => $product->rating,
                ]
        );
        }
    }
}
