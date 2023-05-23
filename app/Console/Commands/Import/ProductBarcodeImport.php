<?php

namespace App\Console\Commands\Import;

use App\Models\Product;
use App\Models\ProductBarcode;
use App\Models\Role;
use Illuminate\Console\Command;

class ProductBarcodeImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:productBarcode';

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
        foreach (\DB::connection('boszhan')->table('product_barcodes')->get() as $productBarcode) {
            ProductBarcode::updateOrCreate(
                ['id' => $productBarcode->id],
                [
                    'id' => $productBarcode->id,
                    'barcode' => $productBarcode->barcode,
                    'product_id' => $productBarcode->product_id,
                ]
        );
        }
    }
}
