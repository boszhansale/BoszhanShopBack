<?php

namespace App\Console\Commands\Onec;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Store;
use Carbon\Carbon;
use Exception;
use File;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class ReportTotalOrderCommand extends Command
{
    protected $signature = 'onec:total_order';

    protected $description = 'Generate order report by orders to xml';

    public function handle()
    {
        $stores = Store::whereNotNull('warehouse_in')->get();

        foreach ($stores as $store) {
                 $startDate = Carbon::parse('2023-07-21');
                 $endDate = Carbon::parse('2023-12-31');

                $orderProducts = OrderProduct::query()
                    ->join('orders','orders.id','order_products.order_id')
                    ->where('orders.store_id',$store->id)

                    ->whereDate('order_products.created_at','>=',$startDate)
                    ->whereDate('order_products.created_at','<=',$endDate)
                    ->selectRaw('product_id,SUM(order_products.`count`) AS count, SUM(order_products.all_price) AS all_price,price')
                    ->groupBy('product_id','price')
                    ->with('product')
                    ->get();
//                    $this->info("orderProduct count: ".count($orderProducts));
                    if (count($orderProducts) == 0){
                        continue;
                    }


                    $idOnec  = $store->counteragent?->id_1c;
                    if (!$idOnec){
                        continue;
                    }
                    $idSell = 300000000000000 + $store->id;

                    $dateName = $startDate->clone()->format('Y-m-d').'_'.$endDate->clone()->format('Y-m-d');
                    $name = "ORDER_{$dateName}_{$idOnec}_9864232489962_{$store->id}.xml";;
                    $path = "reports/$dateName/$name";

                    $output = View::make('onec.report_order', compact('orderProducts','store', 'idOnec', 'idSell','startDate'))->render();
                    $output = '<?xml version="1.0" encoding="utf-8"?>'."\n". $output;

                    Storage::put($path, $output);
                    if (File::exists("/home/dev/ftt/$store->id/$name")) {
                        File::delete("/home/dev/ftt/$store->id/$name");
                    }

                    File::put("/home/dev/ftt/$store->id/$name", $output);

                    $this->info("The report   is saved here : $path, type is 0");

        }
    }
}
