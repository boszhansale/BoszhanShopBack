<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AuthLoginRequest;
use App\Http\Requests\Api\OrderCheckRequest;
use App\Http\Requests\Api\MovingIndexRequest;
use App\Http\Requests\Api\MovingStoreRequest;
use App\Models\Moving;
use App\Models\MovingProduct;
use App\Models\Product;
use App\Services\WebKassa\WebKassaService;
use Illuminate\Support\Facades\Auth;
//Поступление товара
class MovingController extends Controller
{
    public function index(MovingIndexRequest $request)
    {
        $movings = Moving::query()
            ->where('movings.user_id',Auth::id())
            ->when($request->has('date_from'),function ($q){
                $q->whereDate('created_at','>=',request('date_from'));
            })
            ->when($request->has('date_to'),function ($q){
                $q->whereDate('created_at','<=',request('date_to'));
            })
            ->with(['products','products.product','store'])
            ->latest()
            ->get();
        return response()->json($movings);
    }

    public function history(MovingIndexRequest $request)
    {
        $movings = Moving::query()
            ->where('movings.user_id',Auth::id())
            ->when($request->has('date_from'),function ($q){
                $q->whereDate('created_at','>=',request('date_from'));
            })
            ->when($request->has('date_to'),function ($q){
                $q->whereDate('created_at','<=',request('date_to'));
            })
            ->with(['products','products.product','store'])
            ->latest()
            ->get();
        return response()->json($movings);
    }

    public function store(MovingStoreRequest $request)
    {
        $data = [];
        $data['storage_id'] = $request->has('storage_id') ? $request->get('storage_id') : Auth::user()->storage_id;
        $data['store_id'] = Auth::user()->store_id;

        $moving = Auth::user()->movings()->create(array_merge($request->validated(),$data));
        if ($request->has('products'))
        {
            foreach ($request->get('products') as $item) {
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



        return response()->json($moving);

    }

    public function delete(Moving $moving)
    {
        $moving->delete();
        return response()->json($moving);
    }


}
