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

class ReportOrderCommand extends Command
{
    protected $signature = 'onec:order {order_id?}';

    protected $description = 'Generate order report by orders to xml';

    public function handle()
    {
        $order_id = $this->argument('order_id');

        $stores = Store::whereNotNull('warehouse_in')->get();

        foreach ($stores as $store) {
//            $startDate = now()->subWeeks(2)->startOfWeek()->subDay();
            $startDate = Carbon::parse('2023-07-21');
            while ($startDate->lte( now() )){
                $startDate->addDay();
                $date = $startDate->clone();
                $orderProducts = OrderProduct::query()
                    ->join('orders','orders.id','order_products.order_id')
                    ->where('orders.store_id',$store->id)

                    ->whereDate('order_products.created_at',$startDate)
                    ->selectRaw('product_id,SUM(order_products.`count`) AS COUNT, SUM(order_products.all_price) AS all_price,ROUND(MAX(order_products.price)) AS price')
                    ->groupBy('product_id')
                    ->with('product')
                    ->get();


                    if (count($orderProducts) == 0){
                        continue;
                    }

                    $idOnec  = $store->counteragent?->id_1c;
                    if (!$idOnec){
                        continue;
                    }
                    $idSell = 300000000000000 + $store->id;


                    $name = "ORDER_{$date->clone()->format('YmdHis')}_{$idOnec}_9864232489962_{$store->id}.xml";;
                    $path = "reports/" . $date->format('Y-m-d') . "/$name";

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




            ///////////////////////////////////////////////////////////////////


            ///////////////////////////////////////////////////////////////////


    }
}
