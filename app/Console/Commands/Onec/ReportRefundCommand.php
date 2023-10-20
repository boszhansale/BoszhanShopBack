<?php

namespace App\Console\Commands\Onec;

use App\Models\Order;
use App\Models\Refund;
use Carbon\Carbon;
use Exception;
use File;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class ReportRefundCommand extends Command
{
    protected $signature = 'onec:refund {refund_id?}';

    protected $description = 'Generate refund report by orders to xml';

    public function handle()
    {
        $refundId = $this->argument('refund_id');

        $startDate = now()->startOfWeek()->subDay();
        while ($startDate->lte( now() )) {
            $startDate->addDay();
            $refunds = Refund::query()
                ->when($refundId, function ($q) use ($refundId) {
                    return $q->where('refunds.id', $refundId);
                }, function ($q) {
//                return $q->whereDate('created_at', now());
                })
                ->whereDate('created_at',$startDate)
                ->get();

            if (count($refunds) == 0) {
                $this->info('нет возврат');
                return 0;
            }
           // $todayDate = now()->format('Y-m-d');


            foreach ($refunds as $refund) {

                ///////////////////////////////////////////////////////////////////
                try {

                    if (!$refund->store){
                        throw new Exception('нет магазина');
                    }
                    $idOnec = $refund->counteragent?->id_1c ?? $refund->store->counteragent?->id_1c;
                    if (!$idOnec){
                        throw new Exception('нет контрагента');
                    }

                    $idSell = 300000000000000 + $refund->store_id;

                    //$date = Carbon::parse($refund->created_at)->addDay(); //->format('Y-m-d');
//            $date = $dateObj->format('Y-m-d');
                    $name = "REFUND_{$startDate->clone()->format('YmdHis')}_{$idOnec}_9864232489962_{$refund->id}.xml";;
                    $path = "reports/" . $startDate->clone()->format('Y-m-d'). "/$name";

                    $output = View::make('onec.report_refund', compact('refund', 'idOnec', 'idSell','startDate'))->render();
                    $output = '<?xml version="1.0" encoding="utf-8"?>'."\n". $output;

                    Storage::put($path, $output);
                    if (File::exists("/home/dev/index/test/$name")) {
                        File::delete("/home/dev/index/test/$name");
                    }
                    File::put("/home/dev/index/test/$name", $output);

                    $this->info("The report for order $refund->id is saved here : $path, type is 0");


                } catch (Exception $exception) {
                    $this->error($exception->getMessage());
                }

                ///////////////////////////////////////////////////////////////////


            }
        }

    }
}
