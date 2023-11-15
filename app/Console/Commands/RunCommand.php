<?php

namespace App\Console\Commands;

use App\Imports\MovingImport;
use App\Models\Order;
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
        Excel::import(new MovingImport(), 'movings.xlsx');



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
    }
}
