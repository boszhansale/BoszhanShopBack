<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ReportDiscountCardRequest;
use App\Http\Requests\Api\ReportProductRequest;
use App\Http\Requests\Api\ReportRemainsRequest;
use App\Models\DiscountCard;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    //остатки
    public function remain(Store $store)
    {
        return view('admin.report.remain',compact('store'));
    }

    public function discountCard(Store $store)
    {

        return view('admin.report.discount_card',compact('store'));
    }

    public function order(Store $store)
    {

        return view('admin.report.order',compact('store'));
    }

    public function inventor(Store $store)
    {

        return view('admin.report.inventory',compact('store'));
    }

    public function product(Store $store)
    {

//            Остаток на начало = remains_start
//            Поступило = receipt_all
//            Возврат от покупателя = refund
//            Поступление от поставщика = receipt
//            Излишки = overage
//            Перемещение со склада = moving_from
//            Списано общее = reject
//            Списание = reject_all
//            Возврат поставщику = refund_producer
//            Продажи = order
//            Перемещение на склад = moving_to
//            Остатки на конец = remains_end



        return view('admin.report.product',compact('store'));
    }
}
