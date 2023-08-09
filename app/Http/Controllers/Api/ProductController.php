<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProductIndexRequest;
use App\Http\Resources\ProductResource;
use App\Models\Counteragent;
use App\Models\Product;
use DB;

class ProductController extends Controller
{
    public function index(ProductIndexRequest $request)
    {

//        $priceTypeId = 3;
        if ($request->has('counteragent_id')){
            $counteragent = Counteragent::find($request->get('counteragent_id'));
            $priceTypeId = $counteragent->price_type_id;
        }

//        $products = Product::query()
//            ->join('product_price_types','product_price_types.product_id','products.id')
//            ->join('product_barcodes','product_barcodes.product_id','products.id')
//            ->where('product_price_types.price_type_id',$priceTypeId)
//            ->when($request->has('category_id'), function ($query) {
//                return $query->where('category_id', request('category_id'));
//            })
//            ->when($request->has('search'), function ($query) {
//                return $query->where(function ($q) {
//                    $q->where('id_1c','LIKE','%'.request('search').'%')
//                        ->orWhere('article','LIKE','%'.request('search').'%')
//                        ->orWhere('name','LIKE','%'.request('search').'%')
//                        ->orWhere('products.barcode','LIKE','%'.request('search').'%')
//                        ->orWhere('product_barcodes.barcode','LIKE','%'.request('search').'%');
//                });
//            })
//            ->where('products.remainder', '>', 0)
//            ->with(['images'])
//            ->select('products.*','product_price_types.price','product_price_types.price_type_id')
//            ->groupBy('products.id')
//            ->orderBy('name')
//            ->get();
        $products = Product::query()
//            ->join('product_price_types', 'product_price_types.product_id', 'products.id')
            ->leftJoin('product_barcodes', 'product_barcodes.product_id', 'products.id')
//            ->where('product_price_types.price_type_id', $priceTypeId)
            ->when($request->has('category_id'), function ($query) {
                return $query->where('category_id', request('category_id'));
            })
            ->when($request->has('search'), function ($query) {
                return $query->where(function ($q) {
                    $searchTerm = '%' . request('search') . '%';
                    $q->where('id_1c', 'LIKE', $searchTerm)
                        ->orWhere('article', 'LIKE', $searchTerm)
                        ->orWhere('name', 'LIKE', $searchTerm)
                        ->orWhere('products.barcode', 'LIKE', $searchTerm)
                        ->orWhere('product_barcodes.barcode', 'LIKE', $searchTerm);
                });
            })
            ->where('products.remainder', '>', 0)
//            ->where('product_price_types.price_type_id',  3)
            ->with(['images','barcodes'])
//            ->select('products.*', DB::raw('MAX(product_price_types.price) AS price'), 'product_price_types.price_type_id')
            ->select('products.*')
            ->groupBy('products.id')
            ->orderBy('name')
            ->get();

        return response()->json(ProductResource::collection($products));
    }
}
