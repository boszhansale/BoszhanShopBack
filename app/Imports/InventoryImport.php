<?php

namespace App\Imports;

use App\Models\Moving;
use App\Models\MovingProduct;
use App\Models\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class InventoryImport implements ToCollection, WithStartRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $movingId = 12;
        foreach ($collection as $item) {
            if (!$item[3]) continue;

            $product = Product::where('article',$item[3])->first();
            if (!$product)
            {
                dd('product not found: '.$item[3]);
            }



            MovingProduct::updateOrCreate([
                'moving_id' => $movingId,
                'product_id' => $product->id
            ],[
                'moving_id' => $movingId,
                'product_id' => $product->id,
                'count' => $item['22'],
                'price' => $item['33'],
                'all_price' => $item[22] * $item[33],
            ]);



        }

    }

    public function startRow(): int
    {
        return 12;
    }
}
