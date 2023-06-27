<?php

namespace App\Console\Commands\Onec;

use App\Models\Order;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class ReportRejectCommand extends Command
{
    protected $signature = 'onec:reject {reject_id?}';

    protected $description = 'Generate reject report by rejects to xml';

    public function handle()
    {
        $reject_id = $this->argument('reject_id');

        $rejects = Order::query()
            ->when($reject_id, function ($q) use ($reject_id) {
                return $q->where('rejects.id', $reject_id);
            }, function ($q) {
//                return $q->whereDate('created_at', now());
            })
            ->get();
        if (count($rejects) == 0) {
            $this->info('нет заказов');
            return 0;
        }
        foreach ($rejects as $reject) {
            try {
                if (!$reject->store){
                    throw new Exception('нет магазина');
                }
                $idOnec =$reject->counteragent?->id_1c ?? $reject->store->counteragent?->id_1c;
                if (!$idOnec){
                    throw new Exception('нет контрагента');
                }
                $idSell = 300000000000000 + $reject->store_id;

                $date = Carbon::parse($reject->created_at)->addDay();
                $name = "REJECT_{$date->clone()->format('YmdHis')}_{$idOnec}_9864232489962_{$reject->id}.xml";;
                $path = "reports/" . now()->format('Y-m-d') . "/$name";

                $output = View::make('onec.report_reject', compact('reject', 'idOnec', 'idSell'))->render();
                $output = '<?xml version="1.0" encoding="utf-8"?>\n' . $output;

                Storage::put($path, $output);
//
//                if ($reject->number) {
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
                $this->info("The report for reject $reject->id is saved here : $path");
            } catch (Exception $exception) {
                $this->error($exception->getMessage());
            }

        }
    }
}
