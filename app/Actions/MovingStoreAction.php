<?php

namespace App\Actions;

use App\Models\Moving;
use App\Models\MovingProduct;
use App\Models\Product;
use App\Models\ReceiptProduct;
use App\Models\Reject;
use Exception;
use Illuminate\Support\Facades\Auth;

class MovingStoreAction
{

    /**
     * @throws Exception
     */
    public function execute($data): Moving
    {
        $moving = Auth::user()->movings()->create($data);
        if (isset($data['products']))
        {
            foreach ($data['products'] as $item) {
                $product = Product::find($item['product_id']);
                if (!$product) continue;
                $movingProduct = MovingProduct::query()->join('movings','movings.id','moving_products.Moving_id')
                    ->where('movings.user_id',Auth::id())
                    ->where('moving_products.product_id',$item['product_id'])
                    ->select('moving_products.*')
                    ->latest()
                    ->first();
                if ($movingProduct){
                    $item['old_price'] = $movingProduct->price;
                }
                $item['all_price'] = $item['count'] * $item['price'];

                $moving->products()->updateOrCreate([
                    'product_id' => $product->id,
                    'moving_id' => $moving->id
                ],$item);
            }
            $moving->update([
                'product_history' => $moving->products()->select('product_id','count','price','all_price','comment')->get()->toArray(),
                'total_price' => $moving->products()->sum('all_price')
            ]);
        }
        if ($moving->operation == 1 ){
            $receiptAction = new ReceiptStoreAction();

            $receiptAction->execute([
                'source' => 2,
                'operation' => 1,
                'moving_id' => $moving->id,
                'products' => $data['products'],
                'storage_id' => Auth::user()->storage_id,
                'store_id' => Auth::user()->store_id,
                'organization_id' => Auth::user()->organization_id,
            ]);
        }
        if ($moving->operation == 2 ){
            $rejectAction = new RejectStoreAction();

            $rejectAction->execute([
                'source' => 2,
                'storage_id' => Auth::user()->storage_id,
                'store_id' => Auth::user()->store_id,
                'organization_id' => Auth::user()->organization_id,
                'moving_id' => $moving->id,
                'products' => $data['products'],
            ]);
        }



        return  $moving;
    }

}
