<?php

namespace App\Console\Commands;

use App\Imports\MovingImport;
use App\Models\Inventory;
use App\Models\MovingProduct;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\ReceiptProduct;
use App\Models\User;
use App\Services\WebKassa\WebKassaService;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class RunCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Excel::import(new MovingImport(), 'movings.xlsx');


//        $orders = Order::all();
//
//        foreach ($orders as $order) {
//            $sum = 0;
//            if ($order->payments){
//                foreach ($order->payments as $item) {
//                    $sum += $item['Sum'];
//                }
//                $order->give_price = $sum - $order->total_price;
//            }else{
//                $order->give_price = 0;
//            }
//
//            $order->save();
//        }
//        dd(WebKassaService::authorize(User::find(2503)));
        $store_id = 6;
        $inventories = Inventory::query()
            ->where('store_id', $store_id)
            ->where('status', 2)
            ->get();
        $oldInventory = null;

        foreach ($inventories as $inventory) {
            if (!$oldInventory) {
                foreach ($inventory->products as $product) {
                    $product->between_sale = OrderProduct::query()
                        ->join('orders', 'orders.id', 'order_products.order_id')
                        ->where('orders.store_id', $store_id)
                        ->where('order_products.product_id', $product->product_id)
                        ->whereNotNull('orders.check_number')
                        ->where(function ($q) use ($inventory) {
                            $q->where(function ($qq) use ($inventory) {
                                $qq->whereDate('orders.created_at', '=', $inventory->created_at)->whereTime('orders.created_at', '<=', $inventory->created_at);
                            })->orWhereDate('orders.created_at', '<', $inventory->created_at);
                        })
                        ->sum("count");

                    $product->between_receipt = ReceiptProduct::query()
                        ->join('receipts', 'receipts.id', 'receipt_products.receipt_id')
                        ->where('receipts.store_id', $store_id)
                        ->where('receipt_products.product_id', $product->product_id)
                        ->where(function ($q) use ($inventory) {
                            $q->where(function ($qq) use ($inventory) {
                                $qq->whereDate('receipts.created_at', '=', $inventory->created_at)->whereTime('receipts.created_at', '<=', $inventory->created_at);
                            })->orWhereDate('receipts.created_at', '<', $inventory->created_at);
                        })
                        ->sum("count");
                    $product->between_moving_from = MovingProduct::query()
                        ->join('movings', 'movings.id', 'moving_products.moving_id')
                        ->where('movings.store_id', $store_id)
                        ->where('moving_products.product_id', $product->product_id)
                        ->where('movings.operation', 1)
                        ->where(function ($q) use ($inventory) {
                            $q->where(function ($qq) use ($inventory) {
                                $qq->whereDate('movings.created_at', '=', $inventory->created_at)->whereTime('movings.created_at', '<=', $inventory->created_at);
                            })->orWhereDate('movings.created_at', '<', $inventory->created_at);
                        })
                        ->sum("count");
                    $product->between_moving_to = MovingProduct::query()
                        ->join('movings', 'movings.id', 'moving_products.moving_id')
                        ->where('movings.store_id', $store_id)
                        ->where('moving_products.product_id', $product->product_id)
                        ->where('movings.operation', 2)
                        ->where(function ($q) use ($inventory) {
                            $q->where(function ($qq) use ($inventory) {
                                $qq->whereDate('movings.created_at', '=', $inventory->created_at)->whereTime('movings.created_at', '<=', $inventory->created_at);
                            })->orWhereDate('movings.created_at', '<', $inventory->created_at);
                        })
                        ->sum("count");




                    $product->save();
                }
                $oldInventory = $inventory;
                continue;
            }

            foreach ($inventory->products as $product) {
                $product->between_sale = OrderProduct::query()
                    ->join('orders', 'orders.id', 'order_products.order_id')
                    ->where('orders.store_id', $store_id)
                    ->where('order_products.product_id', $product->product_id)
                    ->whereNotNull('orders.check_number')
                    ->where(function ($q) use ($oldInventory) {
                        $q->where(function ($qq) use ($oldInventory) {
                            $qq->whereDate('orders.created_at', '=', $oldInventory->created_at)->whereTime('orders.created_at', '>=', $oldInventory->created_at);
                        })->orWhereDate('orders.created_at', '>', $oldInventory->created_at);
                    })
                    ->where(function ($q) use ($inventory) {
                        $q->where(function ($qq) use ($inventory) {
                            $qq->whereDate('orders.created_at', '=', $inventory->created_at)->whereTime('orders.created_at', '<=', $inventory->created_at);
                        })->orWhereDate('orders.created_at', '<', $inventory->created_at);
                    })
                    ->sum("count");


                $product->between_receipt = ReceiptProduct::query()
                    ->join('receipts', 'receipts.id', 'receipt_products.receipt_id')
                    ->where('receipts.store_id', $store_id)
                    ->where('receipt_products.product_id', $product->product_id)
                    ->where(function ($q) use ($oldInventory) {
                        $q->where(function ($qq) use ($oldInventory) {
                            $qq->whereDate('receipts.created_at', '=', $oldInventory->created_at)->whereTime('receipts.created_at', '>=', $oldInventory->created_at);
                        })->orWhereDate('receipts.created_at', '>', $oldInventory->created_at);
                    })
                    ->where(function ($q) use ($inventory) {
                        $q->where(function ($qq) use ($inventory) {
                            $qq->whereDate('receipts.created_at', '=', $inventory->created_at)->whereTime('receipts.created_at', '<=', $inventory->created_at);
                        })->orWhereDate('receipts.created_at', '<', $inventory->created_at);
                    })
                    ->sum("count");
                $product->between_moving_from = MovingProduct::query()
                    ->join('movings', 'movings.id', 'moving_products.moving_id')
                    ->where('movings.store_id', $store_id)
                    ->where('moving_products.product_id', $product->product_id)
                    ->where('movings.operation', 1)
                    ->where(function ($q) use ($oldInventory) {
                        $q->where(function ($qq) use ($oldInventory) {
                            $qq->whereDate('movings.created_at', '=', $oldInventory->created_at)->whereTime('movings.created_at', '>=', $oldInventory->created_at);
                        })->orWhereDate('movings.created_at', '>', $oldInventory->created_at);
                    })
                    ->where(function ($q) use ($inventory) {
                        $q->where(function ($qq) use ($inventory) {
                            $qq->whereDate('movings.created_at', '=', $inventory->created_at)->whereTime('movings.created_at', '<=', $inventory->created_at);
                        })->orWhereDate('movings.created_at', '<', $inventory->created_at);
                    })
                    ->sum("count");
                $product->between_moving_to = MovingProduct::query()
                    ->join('movings', 'movings.id', 'moving_products.moving_id')
                    ->where('movings.store_id', $store_id)
                    ->where('moving_products.product_id', $product->product_id)
                    ->where('movings.operation', 2)
                    ->where(function ($q) use ($oldInventory) {
                        $q->where(function ($qq) use ($oldInventory) {
                            $qq->whereDate('movings.created_at', '=', $oldInventory->created_at)->whereTime('movings.created_at', '>=', $oldInventory->created_at);
                        })->orWhereDate('movings.created_at', '>', $oldInventory->created_at);
                    })
                    ->where(function ($q) use ($inventory) {
                        $q->where(function ($qq) use ($inventory) {
                            $qq->whereDate('movings.created_at', '=', $inventory->created_at)->whereTime('movings.created_at', '<=', $inventory->created_at);
                        })->orWhereDate('movings.created_at', '<', $inventory->created_at);
                    })
                    ->sum("count");


                $product->save();
            }

            $oldInventory = $inventory;
        }


    }
}
