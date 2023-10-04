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
            ->where('name','LIKE','%акция%')
            ->orderBy('name')
            ->get();

        return view('admin.storeProductPromotion.create', compact('store','products'));
    }

    public function store(Request $request): RedirectResponse
    {

        $spd = new StoreProductPromotion();
        $spd->store_id = $request->get('store_id');
        $spd->date_from = $request->get('date_from');
        $spd->date_to = $request->get('date_to');
        $spd->product_id = $request->get('product_id');
        $spd->count = $request->get('count');
        $spd->price = $request->get('price');
        $spd->price_condition = $request->get('price_condition');
        $spd->online_sale = $request->has('online_sale');
        $spd->save();


        return redirect()->route('admin.storeProductPromotion.index',$request->get('store_id'));
    }

    public function edit(StoreProductPromotion $storeProductPromotion)
    {
        $products = Product::query()
            ->orderBy('article')
            ->where('name','LIKE','%акция%')
            ->orderBy('name')
            ->get();

        return \view('admin.storeProductPromotion.edit',compact('storeProductPromotion','products'));
    }

    public function update(Request $request,StoreProductPromotion $storeProductPromotion)
    {

        $storeProductPromotion->date_from = $request->get('date_from');
        $storeProductPromotion->date_to = $request->get('date_to');
        $storeProductPromotion->product_id = $request->get('product_id');
        $storeProductPromotion->count = $request->get('count');
        $storeProductPromotion->price = $request->get('price');
        $storeProductPromotion->price_condition = $request->get('price_condition');
        $storeProductPromotion->online_sale = $request->has('online_sale');
        $storeProductPromotion->save();
        return redirect()->route('admin.storeProductPromotion.index',$storeProductPromotion->store_id);
    }

    public function delete(StoreProductPromotion $storeProductPromotion)
    {
        $storeProductPromotion->delete();
        return redirect()->back();
    }

}
