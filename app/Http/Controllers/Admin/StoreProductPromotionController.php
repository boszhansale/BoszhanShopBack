<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreStoreRequest;
use App\Models\Counteragent;
use App\Models\Product;
use App\Models\ProductPriceType;
use App\Models\Store;
use App\Models\StoreProductDiscount;
use App\Models\StoreProductPromotion;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StoreProductPromotionController extends Controller
{
    public function index(Store $store)
    {


        $productPromotions = StoreProductPromotion::query()
            ->join('products','products.id','store_product_promotions.product_id')
            ->select(['store_product_promotions.*','products.article','products.name'])
            ->where('store_product_promotions.store_id',$store->id)
            ->orderBy('date_from')
            ->get();


        return view('admin.storeProductPromotion.index', compact('productPromotions','store'));
    }

    public function create(Store $store)
    {

        $products = Product::query()
            ->orderBy('article')
            ->orderBy('name')
            ->get();

        return view('admin.storeProductPromotion.create', compact('store','products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $productPrice = ProductPriceType::where('product_id',$request->get('product_id'))->where('price_type_id',3)->firstOrFail();
        $discount = $request->get('discount');
        $price = $discount ? ($productPrice->price / 100) * $discount : $request->get('price');


        $spd = new StoreProductDiscount();
        $spd->store_id = $request->get('store_id');
        $spd->date_from = $request->get('date_from');
        $spd->date_to = $request->get('date_to');
        $spd->product_id = $request->get('product_id');
        $spd->discount = $discount;
        $spd->price = $price;
        $spd->save();


        return redirect()->route('admin.storeProductDiscount.index',$request->get('store_id'));
    }

    public function edit()
    {

    }

    public function update(Request $request,StoreProductPromotion $storeProductPromotion)
    {


        return redirect()->back();
    }

    public function delete(StoreProductDiscount $storeProductDiscount)
    {
        $storeProductDiscount->delete();
        return redirect()->back();
    }

}
