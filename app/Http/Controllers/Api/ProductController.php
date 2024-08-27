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
        if ($request->has('counteragent_id')) {
            $counteragent = Counteragent::find($request->get('counteragent_id'));
            $priceTypeId = $counteragent->price_type_id;
        }
        $products = Product::query()
            ->leftJoin('product_barcodes', 'product_barcodes.product_id', 'products.id')
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
            ->when($request->has('id'), function ($query) {
                return $query->where('products.id', request('id'));
            })
            ->with(['images', 'barcodes'])
            ->select('products.*')
            ->groupBy('products.id')
            ->orderBy('name')
            ->get();

        return response()->json(ProductResource::collection($products));
    }
}
