<?php

namespace App\Console\Commands\Onec;

use App\Models\Order;
use App\Models\Receipt;
use Carbon\Carbon;
use Exception;
use File;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class ReportReceiptCommand extends Command
{
    protected $signature = 'onec:receipt {receipt_id?}';

    protected $description = 'Generate order report by receipts to xml';

    public function handle()
    {

        $receiptId = $this->argument('receipt_id');

        $startDate = now()->startOfWeek()->subDay();
        while ($startDate->lte( now() )) {
            $startDate->addDay();
            $receipts = Receipt::query()
                ->when($receiptId, function ($q) use ($receiptId) {
                    return $q->where('receipts.id', $receiptId);
                }, function ($q) {
//                return $q->whereDate('created_at', now());
                })
                ->whereDate('created_at',$startDate)
                ->get();

            if (count($receipts) == 0) {
                $this->info('нет заказов');
                return 0;
            }

            foreach ($receipts as $receipt) {
                try {
                    if (!$receipt->store){
                        throw new Exception('нет магазина');
                    }
                    $idOnec =$receipt->counteragent?->id_1c ?? $receipt->store->counteragent?->id_1c;
                    if (!$idOnec){
                        throw new Exception('нет контрагента');
                    }
                    $idSell = 300000000000000 + $receipt->store_id;

                    //$date = Carbon::parse($receipt->created_at)->addDay(); //->format('Y-m-d');
                    $name = "RECEIPT_{$startDate->clone()->format('YmdHis')}_{$idOnec}_9864232489962_{$receipt->id}.xml";;
                    $path = "reports/" . $startDate->clone()->format('Y-m-d') . "/$name";
                    $output = View::make('onec.report_receipt', compact('receipt', 'idOnec', 'idSell','startDate'))->render();
                    $output = '<?xml version="1.0" encoding="utf-8"?>'."\n". $output;
                    Storage::put($path, $output);
                    if (File::exists("/home/dev/index/test/$name")) {
                        File::delete("/home/dev/index/test/$name");
                    }
                    File::put("/home/dev/index/test/$name", $output);

                    $this->info("The report for receipt $receipt->id is saved here : $path");
                } catch (Exception $exception) {
                    $this->error($exception->getMessage());
                }
            }
        }



    }
}
