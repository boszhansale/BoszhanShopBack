<?php

namespace App\Console\Commands\Onec;

use App\Models\Order;
use Carbon\Carbon;
use Exception;
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

        $orders = Order::query()
            ->when($order_id, function ($q) use ($order_id) {
                return $q->where('orders.id', $order_id);
            }, function ($q) {
//                return $q->whereDate('created_at', now());
            })
            ->whereNull('orders.removed_at')
            ->get();

        if (count($orders) == 0) {
            $this->info('нет заказов');
            return 0;
        }
        $todayDate = now()->format('Y-m-d');


        foreach ($orders as $order) {

            ///////////////////////////////////////////////////////////////////
            try {

                if (!$order->store){
                    throw new Exception('нет магазина');
                }

                $idOnec =$order->counteragent?->id_1c ?? $order->store->counteragent?->id_1c;
                if (!$idOnec){
                    throw new Exception('нет контрагента');
                }
                $idSell = 300000000000000 + $order->store_id;

                $date = Carbon::parse($order->created_at)->addDay(); //->format('Y-m-d');
//            $date = $dateObj->format('Y-m-d');
                $name = "ORDER_{$date->clone()->format('YmdHis')}_{$idOnec}_9864232489962_{$order->id}.xml";;
                $path = "reports/" . now()->format('Y-m-d') . "/$name";

                $output = View::make('onec.report_order', compact('order', 'idOnec', 'idSell'))->render();
                $output = '<?xml version="1.0" encoding="utf-8"?>\n' . $output;

                Storage::put($path, $output);
//
//                if ($order->number) {
//                    if (File::exists("/home/dev/index/edi/$name")) {
//                        File::delete("/home/dev/index/edi/$name");
//                    }
//                    File::put("/home/dev/index/edi/$name", $output);
//                } else {
//                    if (File::exists("/home/dev/index/$name")) {
//                        File::delete("/home/dev/index/$name");
//                    }
//                    File::put("/home/dev/index/$name", $output);
//                }
                $this->info("The report for order $order->id is saved here : $path, type is 0");


            } catch (Exception $exception) {
                $this->error($exception->getMessage());
            }

            ///////////////////////////////////////////////////////////////////


        }
    }
}
