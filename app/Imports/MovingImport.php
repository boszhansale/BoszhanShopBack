<?php

namespace App\Imports;

use App\Models\Moving;
use App\Models\MovingProduct;
use App\Models\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class MovingImport implements ToCollection, WithStartRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $movingId = 88;
        foreach ($collection as $item) {
            if (!$item[3]) continue;
            $product = Product::where('article',$item[3])->first();
            if (!$product)
            {
                dump($item[5]);
                continue;
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
