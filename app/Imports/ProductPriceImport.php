<?php

namespace App\Imports;

use App\Models\PriceType;
use App\Models\Product;
use App\Models\ProductPriceType;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ProductPriceImport implements ToCollection, WithStartRow
{

    public PriceType $priceType;

    public function __construct(PriceType $priceType)
    {
        $this->priceType = $priceType;
    }


    /**
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $product = Product::where('article', $row['0'])->first();
            if (!$product) {
                continue;
            }
            ProductPriceType::updateOrCreate([
                'product_id' => $product->id,
                'price_type_id' => $this->priceType->id, 
            ], [
                'product_id' => $product->id,
                'price_type_id' => $this->priceType->id,
                'price' => $row['1']
            ]);

        }

    }

    public function startRow(): int
    {
        return 2;
    }
}
