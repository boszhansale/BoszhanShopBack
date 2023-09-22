<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreStoreRequest;
use App\Models\Counteragent;
use App\Models\DiscountCard;
use App\Models\Store;
use App\Models\StoreProductDiscount;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DiscountCardController extends Controller
{
    public function index($storeId)
    {
        $discountCards = DiscountCard::where('store_id',$storeId)->latest()->get();
        return view('admin.discountCard.index', compact('storeId','discountCards'));
    }

    public function create($storeId)
    {

        return view('admin.discountCard.create', compact('storeId'));
    }

    public function store(Request $request): RedirectResponse
    {

        DiscountCard::updateOrCreate([
            'store_id' => $request->get('store_id'),
            'phone' => $request->get('phone')
        ],[
            'store_id' => $request->get('store_id'),
            'phone' => $request->get('phone'),
            'discount' => $request->get('discount'),
            'cashback' => $request->get('cashback')
        ]);
        return redirect()->route('admin.discountCard.index',$request->get('store_id'));
    }

    public function edit(DiscountCard $discountCard)
    {
        return view('admin.discountCard.edit', compact('discountCard'));
    }

    public function update(Request $request,DiscountCard $discountCard)
    {
        DiscountCard::updateOrCreate([
            'store_id' => $discountCard->store_id,
            'phone' => $request->get('phone')
        ],[
            'store_id' =>  $discountCard->store_id,
            'phone' => $request->get('phone'),
            'discount' => $request->get('discount'),
            'cashback' => $request->get('cashback')
        ]);

        return redirect()->route('admin.discountCard.index',$discountCard->store_id);
    }

    public function delete(DiscountCard $discountCard)
    {
        $discountCard->delete();
        return redirect()->back();
    }



}
