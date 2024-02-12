<?php

namespace App\Console\Commands\Onec;

use App\Models\Moving;
use App\Models\Order;
use Carbon\Carbon;
use Exception;
use File;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class ReportMovingCommand extends Command
{
    protected $signature = 'onec:moving {moving_id?}';

    protected $description = 'Generate moving report by orders to xml';

    public function handle()
    {
        $movingId = $this->argument('moving_id');

        $startDate = now()->subWeeks(2)->startOfWeek()->subDay();
        while ($startDate->lte( now() )) {
            $startDate->addDay();
            $movings = Moving::query()
//                ->when($movingId, function ($q) use ($movingId) {
//                    return $q->where('orders.id', $movingId);
//                }, function ($q) {
////                return $q->whereDate('created_at', now());
//                })
                ->join('stores','movings.store_id','stores.id')
                ->whereNotNull('stores.warehouse_in')
                ->whereDate('movings.created_at',$startDate)
                ->select('movings.*')
                ->get();

            if (count($movings) == 0) {
                $this->info('нет заказов');
                return 0;
            }
            foreach ($movings as $moving) {
                try {

                    if (!$moving->store){
                        throw new Exception('нет магазина');
                    }

                    $idOnec =  $moving->store->counteragent?->id_1c;
                    if (!$idOnec){
                        throw new Exception('нет контрагента');
                    }
                    $idSell = 300000000000000 + $moving->store_id;
                    //$date = Carbon::parse($moving->created_at)->addDay();
                    $name = "MOVING_{$startDate->clone()->format('YmdHis')}_{$idOnec}_9864232489962_{$moving->id}.xml";;
                    $path = "reports/" . $startDate->clone()->format('Y-m-d') . "/$name";

                    $output = View::make('onec.report_moving', compact('moving', 'idOnec', 'idSell','startDate'))->render();
                    $output = '<?xml version="1.0" encoding="utf-8"?>'."\n". $output;

                    Storage::put($path, $output);
                    if (File::exists("/home/dev/index/test/$name")) {
                        File::delete("/home/dev/index/test/$name");
                    }
                    File::put("/home/dev/ftt/$moving->store_id/$name", $output);

                    $this->info("The report for moving $moving->id is saved here : $path");
                } catch (Exception $exception) {
                    $this->error($exception->getMessage());
                }
            }
        }


    }
}
