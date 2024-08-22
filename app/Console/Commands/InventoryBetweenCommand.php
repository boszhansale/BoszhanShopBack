<?php

namespace App\Console\Commands;

use App\Models\Inventory;
use App\Models\MovingProduct;
use App\Models\OrderProduct;
use App\Models\ReceiptProduct;
use Illuminate\Console\Command;

class InventoryBetweenCommand extends Command
{
    protected $signature = 'inventory_between:{id}';

    protected $description = 'Command description';

    public function handle(): void
    {
        $inventory = Inventory::findOrFail($this->argument('id'));

        $oldInventory = Inventory::query()
            ->where('status', 2)
            ->where('store_id', $inventory->store_id)
            ->where('id', '!=', $inventory->id)
            ->orderBy('id', 'desc')
            ->first();

        foreach ($inventory->products as $product) {
            $product->between_sale = OrderProduct::query()
                ->join('orders', 'orders.id', 'order_products.order_id')
                ->where('orders.store_id', $inventory->store_id)
                ->where('order_products.product_id', $product->product_id)
                ->whereNotNull('orders.check_number')
                ->when($oldInventory, function ($query) use ($oldInventory) {
                    $query->where(function ($q) use ($oldInventory) {
                        $q->where(function ($qq) use ($oldInventory) {
                            $qq->whereDate('orders.created_at', '=', $oldInventory->created_at)->whereTime('orders.created_at', '>=', $oldInventory->created_at);
                        })->orWhereDate('orders.created_at', '>', $oldInventory->created_at);
                    });
                })
                ->where(function ($q) use ($inventory) {
                    $q->where(function ($qq) use ($inventory) {
                        $qq->whereDate('orders.created_at', '=', $inventory->created_at)->whereTime('orders.created_at', '<=', $inventory->created_at);
                    })->orWhereDate('orders.created_at', '<', $inventory->created_at);
                })
                ->sum("count");


            $product->between_receipt = ReceiptProduct::query()
                ->join('receipts', 'receipts.id', 'receipt_products.receipt_id')
                ->where('receipts.store_id', $inventory->store_id)
                ->where('receipt_products.product_id', $product->product_id)
                ->when($oldInventory, function ($query) use ($oldInventory) {
                    $query->where(function ($q) use ($oldInventory) {
                        $q->where(function ($qq) use ($oldInventory) {
                            $qq->whereDate('receipts.created_at', '=', $oldInventory->created_at)->whereTime('receipts.created_at', '>=', $oldInventory->created_at);
                        })->orWhereDate('receipts.created_at', '>', $oldInventory->created_at);
                    });
                })
                ->where(function ($q) use ($inventory) {
                    $q->where(function ($qq) use ($inventory) {
                        $qq->whereDate('receipts.created_at', '=', $inventory->created_at)->whereTime('receipts.created_at', '<=', $inventory->created_at);
                    })->orWhereDate('receipts.created_at', '<', $inventory->created_at);
                })
                ->sum("count");
            $product->between_moving_from = MovingProduct::query()
                ->join('movings', 'movings.id', 'moving_products.moving_id')
                ->where('movings.store_id', $inventory->store_id)
                ->where('moving_products.product_id', $product->product_id)
                ->where('movings.operation', 1)
                ->when($oldInventory, function ($query) use ($oldInventory) {
                    $query->where(function ($q) use ($oldInventory) {
                        $q->where(function ($qq) use ($oldInventory) {
                            $qq->whereDate('movings.created_at', '=', $oldInventory->created_at)->whereTime('movings.created_at', '>=', $oldInventory->created_at);
                        })->orWhereDate('movings.created_at', '>', $oldInventory->created_at);
                    });
                })
                ->where(function ($q) use ($inventory) {
                    $q->where(function ($qq) use ($inventory) {
                        $qq->whereDate('movings.created_at', '=', $inventory->created_at)->whereTime('movings.created_at', '<=', $inventory->created_at);
                    })->orWhereDate('movings.created_at', '<', $inventory->created_at);
                })
                ->sum("count");
            $product->between_moving_to = MovingProduct::query()
                ->join('movings', 'movings.id', 'moving_products.moving_id')
                ->where('movings.store_id', $inventory->store_id)
                ->where('moving_products.product_id', $product->product_id)
                ->where('movings.operation', 2)
                ->when($oldInventory,function ($query) use ($oldInventory) {
                    $query->where(function ($q) use ($oldInventory) {
                        $q->where(function ($qq) use ($oldInventory) {
                            $qq->whereDate('movings.created_at', '=', $oldInventory->created_at)->whereTime('movings.created_at', '>=', $oldInventory->created_at);
                        })->orWhereDate('movings.created_at', '>', $oldInventory->created_at);
                    });
                })
                ->where(function ($q) use ($inventory) {
                    $q->where(function ($qq) use ($inventory) {
                        $qq->whereDate('movings.created_at', '=', $inventory->created_at)->whereTime('movings.created_at', '<=', $inventory->created_at);
                    })->orWhereDate('movings.created_at', '<', $inventory->created_at);
                })
                ->sum("count");
            $product->save();
        }


    }
}
