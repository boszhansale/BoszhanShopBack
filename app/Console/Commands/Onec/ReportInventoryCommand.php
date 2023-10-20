<?php

namespace App\Console\Commands\Onec;

use App\Models\Inventory;
use App\Models\Order;
use Carbon\Carbon;
use Exception;
use File;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class ReportInventoryCommand extends Command
{
    protected $signature = 'onec:inventory {inventory_id?}';

    protected $description = 'Generate order report by orders to xml';

    public function handle()
    {
        $inventoryId = $this->argument('inventory_id');

        $startDate = now()->startOfWeek()->subDay();
        while ($startDate->lte( now() )){
            $startDate->addDay();
            $inventories = Inventory::query()
                ->when($inventoryId, function ($q) use ($inventoryId) {
                    return $q->where('inventories.id', $inventoryId);
                }, function ($q) {
//                return $q->whereDate('created_at', now());
                })
                ->whereDate('created_at',$startDate)
                ->get();

            if (count($inventories) == 0) {
                $this->info('нет inventory');
                return 0;
            }

            foreach ($inventories as $inventory) {
                try {
                    if (!$inventory->store){
                        throw new Exception('нет магазина');
                    }
//                $idOnec = $inventory->counteragent?->id_1c ?? $inventory->store->counteragent?->id_1c;
//                if (!$idOnec){
//                    throw new Exception('нет контрагента');
//                }

                    $idOnec = '';
                    $idSell = 300000000000000 + $inventory->store_id;

                    //$date = Carbon::parse($inventory->created_at)->addDay();
                    $name = "INVENTORY_{$startDate->clone()->format('YmdHis')}_{$idOnec}_9864232489962_{$inventory->id}.xml";;
                    $path = "reports/" . $startDate->clone()->format('Y-m-d') . "/$name";

                    $output = View::make('onec.report_inventory', compact('inventory', 'idOnec', 'idSell','startDate'))->render();
                    $output = '<?xml version="1.0" encoding="utf-8"?>'."\n". $output;
                    Storage::put($path, $output);
//
                    if (File::exists("/home/dev/index/test/$name")) {
                        File::delete("/home/dev/index/test/$name");
                    }
                    File::put("/home/dev/index/test/$name", $output);

                    $this->info("The inventory $inventory->id is saved here : $path");
                } catch (Exception $exception) {
                    $this->error($exception->getMessage());
                }
            }
        }


    }
}
