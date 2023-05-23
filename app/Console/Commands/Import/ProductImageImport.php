<?php

namespace App\Console\Commands\Import;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Role;
use Illuminate\Console\Command;

class ProductImageImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:productImage';

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
        foreach (\DB::connection('boszhan')->table('product_images')->get() as $productImage) {
            ProductImage::updateOrCreate(
                ['id' => $productImage->id],
                [
                    'id' => $productImage->id,
                    'name' => $productImage->name,
                    'product_id' => $productImage->product_id,
                    'path' => $productImage->path,
                ]
        );
        }
    }
}
