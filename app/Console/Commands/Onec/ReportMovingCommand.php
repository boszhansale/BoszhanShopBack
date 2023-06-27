<?php

namespace App\Console\Commands\Onec;

use App\Models\Order;
use Carbon\Carbon;
use Exception;
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

        $movings = Order::query()
            ->when($movingId, function ($q) use ($movingId) {
                return $q->where('orders.id', $movingId);
            }, function ($q) {
//                return $q->whereDate('created_at', now());
            })
            ->whereNull('orders.removed_at')
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
                $date = Carbon::parse($moving->created_at)->addDay();
                $name = "MOVING_{$date->clone()->format('YmdHis')}_{$idOnec}_9864232489962_{$moving->id}.xml";;
                $path = "reports/" . now()->format('Y-m-d') . "/$name";

                $output = View::make('onec.report_moving', compact('moving', 'idOnec', 'idSell'))->render();
                $output = '<?xml version="1.0" encoding="utf-8"?>\n' . $output;

                Storage::put($path, $output);
//
//                if ($moving->number) {
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
                $this->info("The report for moving $moving->id is saved here : $path");
            } catch (Exception $exception) {
                $this->error($exception->getMessage());
            }
        }
    }
}
