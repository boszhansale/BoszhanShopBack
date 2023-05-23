<?php
namespace App\Services\WebKassa;

use App\Models\MoneyOperation;
use App\Models\Order;
use App\Models\Receipt;
use App\Models\Refund;
use App\Models\RefundProducer;
use App\Models\User;
use App\Models\WebkassaCheck;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class WebKassaService
{
    /**
     * @throws Exception
     */
    public static function authorize (User $user):string
    {
        if (Carbon::parse($user->webkassa_login_at)->addDay()->gt(now())){
            return  $user->webkassa_token;
        }
        if (!$user->webkassa_login or !$user->webkassa_password){
            throw new Exception('login password not found');
        }

        $response = Http::post(config('services.webkassa.server').'Authorize',[
            'Login' => $user->webkassa_login,
            'Password' => $user->webkassa_password,
        ]);

        if ($response->status() != 200){
            throw new Exception($response->json());
        }
        $data = $response->json();

        if (isset($data['Errors'])){
            throw new Exception($data['Errors'][0]['Text']);
        }
        if (!isset($data['Data'])){
            throw new Exception('response data not found');
        }
        $user->update([
            'webkassa_token' => $data['Data']['Token'],
            'webkassa_login_at' => now()
        ]);
        return $data['Data']['Token'];
    }
    /**
     * @throws Exception
     */
    public static function checkOrder (Order $order,array $payments)
    {
        $user = $order->user;
        $token = self::authorize($user);
        if (!$user->webkassaCashBox?->unique_number){
            throw new Exception('касса не найден');
        }
        if (count($order->products )== 0){
            throw new Exception('продукты не найдены');
        }
        $externalCheckNumber = Str::random(32);
        $params = [
            'Token' => $token,
            'CashboxUniqueNumber' => $user->webkassaCashBox->unique_number,
            'OperationType' => 2,
            'ExternalCheckNumber' => $externalCheckNumber,
            'ExternalOrderNumber' => $order->id,
            'Positions' => [],
            "Payments" => $payments,
        ];
        foreach ($order->products as $key => $product) {
            $params['Positions'][$key] = [
                'Count' => $product->count,
                'Price' => $product->price,
                "TaxPercent" => 12,
                "Tax" => round((($product->all_price )/ 112) * 12,2),
                "TaxType"=> 100,
                "PositionName"=> $product->product->name,
                "PositionCode"=> $product->product_id,
                "UnitCode"=>  $product->measure == 1 ? '896' : '166',
            ];
        }
        $webkassaCheck = WebkassaCheck::create([
            'order_id' => $order->id,
            'webkassa_cash_box_id' => $user->webkassa_cash_box_id,
            'params' => $params,
            'number' => $externalCheckNumber
        ]);

        $response = Http::withBasicAuth('Bearer',config('services.webkassa.key'))
            ->post(config('services.webkassa.server').'Check',$params);

        if ($response->status() != 200){
            throw new Exception($response->json());
        }
        $data = $response->json();
        if (isset($data['Data'])){
            $webkassaCheck->update(
                [
                    'check_number' => $data['Data']['CheckNumber'],
                    'data' => $data,
                    'ticket_url' => $data['Data']['TicketUrl'],
                    'ticket_print_url' => $data['Data']['TicketPrintUrl']
                ]
            );
            return $data;
        }
        if (isset($data['Errors'])){
            $webkassaCheck->update(
                [
                    'data' => $data,
                ]
            );
            throw new Exception($data['Errors'][0]['Text']);
        }
        throw new Exception('response data not found');
    }
    public static function checkRefund (Refund $refund,array $payments)
    {
        $user = $refund->user;
        $token = self::authorize($user);
        if (!$user->webkassaCashBox?->unique_number){
            throw new Exception('касса не найден');
        }
        if (count($refund->products )== 0){
            throw new Exception('продукты не найдены');
        }
        $externalCheckNumber = Str::random(32);
        $params = [
            'Token' => $token,
            'CashboxUniqueNumber' => $user->webkassaCashBox->unique_number,
            'OperationType' => 3,
            'ExternalCheckNumber' => $externalCheckNumber,
            'ExternalOrderNumber' => $refund->id,
            'Positions' => [],
            "Payments" => $payments,
        ];
        foreach ($refund->products as $key => $product) {
            $params['Positions'][$key] = [
                'Count' => $product->count,
                'Price' => $product->price,
                "TaxPercent" => 12,
                "Tax" => round((($product->all_price )/ 112) * 12,2),
                "TaxType"=> 100,
                "PositionName"=> $product->product->name,
                "PositionCode"=> $product->product_id,
                "UnitCode"=>  $product->measure == 1 ? '896' : '166',
            ];
        }
        $webkassaCheck = WebkassaCheck::create([
            'refund_id' => $refund->id,
            'webkassa_cash_box_id' => $user->webkassa_cash_box_id,
            'params' => $params,
            'number' => $externalCheckNumber
        ]);

        $response = Http::withBasicAuth('Bearer',config('services.webkassa.key'))
            ->post(config('services.webkassa.server').'Check',$params);

        if ($response->status() != 200){
            throw new Exception($response->json());
        }
        $data = $response->json();
        if (isset($data['Data'])){
            $webkassaCheck->update(
                [
                    'check_number' => $data['Data']['CheckNumber'],
                    'data' => $data,
                    'ticket_url' => $data['Data']['TicketUrl'],
                    'ticket_print_url' => $data['Data']['TicketPrintUrl']
                ]
            );
            return $data;
        }
        if (isset($data['Errors'])){
            $webkassaCheck->update(
                [
                    'data' => $data,
                ]
            );
            throw new Exception($data['Errors'][0]['Text']);
        }
        throw new Exception('response data not found');
    }
    /**
     * @throws Exception
     */
    public static function checkReceipt (Receipt $receipt, array $payments)
    {
        $user = $receipt->user;
        $token = self::authorize($user);
        if (!$user->webkassaCashBox?->unique_number){
            throw new Exception('касса не найден');
        }
        if (count($receipt->products )== 0){
            throw new Exception('продукты не найдены');
        }
        $externalCheckNumber = Str::random(32);
        $params = [
            'Token' => $token,
            'CashboxUniqueNumber' => $user->webkassaCashBox->unique_number,
            'OperationType' => 0,
            'ExternalCheckNumber' => $externalCheckNumber,
            'ExternalOrderNumber' => $receipt->id,
            'Positions' => [],
            "Payments" => $payments,
        ];
        foreach ($receipt->products as $key => $product) {
            $params['Positions'][$key] = [
                'Count' => $product->count,
                'Price' => $product->price,
                "TaxPercent" => 12,
                "Tax" => round((($product->all_price )/ 112) * 12,2),
                "TaxType"=> 100,
                "PositionName"=> $product->product->name,
                "PositionCode"=> $product->product_id,
                "UnitCode"=>  $product->measure == 1 ? '896' : '166',
            ];
        }
        $webkassaCheck = WebkassaCheck::create([
            'receipt_id' => $receipt->id,
            'webkassa_cash_box_id' => $user->webkassa_cash_box_id,
            'params' => $params,
            'number' => $externalCheckNumber
        ]);

        $response = Http::withBasicAuth('Bearer',config('services.webkassa.key'))
            ->post(config('services.webkassa.server').'Check',$params);

        if ($response->status() != 200){
            throw new Exception($response->json());
        }
        $data = $response->json();
        if (isset($data['Data'])){
            $webkassaCheck->update(
                [
                    'check_number' => $data['Data']['CheckNumber'],
                    'data' => $data,
                    'ticket_url' => $data['Data']['TicketUrl'],
                    'ticket_print_url' => $data['Data']['TicketPrintUrl']
                ]
            );
            return $data;
        }
        if (isset($data['Errors'])){
            $webkassaCheck->update(
                [
                    'data' => $data,
                ]
            );
            throw new Exception($data['Errors'][0]['Text']);
        }
        throw new Exception('response data not found');
    }
    /**
     * @throws Exception
     */
    public static function checkRefundProducer (RefundProducer $refundProducer, array $payments)
    {
        $user = $refundProducer->user;
        $token = self::authorize($user);
        if (!$user->webkassaCashBox?->unique_number){
            throw new Exception('касса не найден');
        }
        if (count($refundProducer->products )== 0){
            throw new Exception('продукты не найдены');
        }
        $externalCheckNumber = Str::random(32);
        $params = [
            'Token' => $token,
            'CashboxUniqueNumber' => $user->webkassaCashBox->unique_number,
            'OperationType' => 0,
            'ExternalCheckNumber' => $externalCheckNumber,
            'ExternalOrderNumber' => $refundProducer->id,
            'Positions' => [],
            "Payments" => $payments,
        ];
        foreach ($refundProducer->products as $key => $product) {
            $params['Positions'][$key] = [
                'Count' => $product->count,
                'Price' => $product->price,
                "TaxPercent" => 12,
                "Tax" => round((($product->all_price )/ 112) * 12,2),
                "TaxType"=> 100,
                "PositionName"=> $product->product->name,
                "PositionCode"=> $product->product_id,
                "UnitCode"=>  $product->measure == 1 ? '896' : '166',
            ];
        }
        $webkassaCheck = WebkassaCheck::create([
            'refund_producer_id' => $refundProducer->id,
            'webkassa_cash_box_id' => $user->webkassa_cash_box_id,
            'params' => $params,
            'number' => $externalCheckNumber
        ]);

        $response = Http::withBasicAuth('Bearer',config('services.webkassa.key'))
            ->post(config('services.webkassa.server').'Check',$params);

        if ($response->status() != 200){
            throw new Exception($response->json());
        }
        $data = $response->json();
        if (isset($data['Data'])){
            $webkassaCheck->update(
                [
                    'check_number' => $data['Data']['CheckNumber'],
                    'data' => $data,
                    'ticket_url' => $data['Data']['TicketUrl'],
                    'ticket_print_url' => $data['Data']['TicketPrintUrl']
                ]
            );
            return $data;
        }
        if (isset($data['Errors'])){
            $webkassaCheck->update(
                [
                    'data' => $data,
                ]
            );
            throw new Exception($data['Errors'][0]['Text']);
        }
        throw new Exception('response data not found');
    }

    public static function moneyOperation (User $user,int $operationType,float $sum)
    {
        $token = self::authorize($user);
        if (!$user->webkassaCashBox?->unique_number){
            throw new Exception('касса не найден');
        }
        $number = Str::random(32);
        $moneyOperation = MoneyOperation::create([
            'sum' => $sum,
            'user_id' => $user->id,
            'operation_type' => $operationType,
            'number' => $number
        ]);
        $response = Http::withBasicAuth('Bearer',config('services.webkassa.key'))
            ->post(config('services.webkassa.server').'MoneyOperation',[
                'Token' => $token,
                'CashboxUniqueNumber' => $user->webkassaCashBox->unique_number,
                'OperationType' => $operationType,
                'Sum' => $sum,
                'ExternalCheckNumber' => $number,
             ]);

        if ($response->status() != 200){
            $moneyOperation->delete();
            throw new Exception($response->json());
        }
        $data = $response->json();
        if (isset($data['Data'])){
            $user->update([
                'balance' => $data['Data']['Sum']
            ]);
            return $data['Data']['Sum'];
        }
        if (isset($data['Errors'])){
            $moneyOperation->delete();
            throw new Exception($data['Errors'][0]['Text']);
        }
        $moneyOperation->delete();
        throw new Exception('response data not found');
    }

    public static function ZReport(User $user)
    {
        $token = self::authorize($user);
        if (!$user->webkassaCashBox?->unique_number){
            throw new Exception('касса не найден');
        }
        $response = Http::withBasicAuth('Bearer',config('services.webkassa.key'))
            ->post(config('services.webkassa.server').'ZReport',[
                'Token' => $token,
                'CashboxUniqueNumber' => $user->webkassaCashBox->unique_number,
             ]);

        if ($response->status() != 200){
            throw new Exception($response->json());
        }
        $data = $response->json();
        if (isset($data['Data'])){

            $user->webkassaCashBox()->update([
                'closed_at' => now()
            ]);

            return $data['Data'];
        }
        if (isset($data['Errors'])){
            throw new Exception($data['Errors'][0]['Text']);
        }
        throw new Exception('response data not found');
    }

    public static function XReport(User $user)
    {
        $token = self::authorize($user);
        if (!$user->webkassaCashBox?->unique_number){
            throw new Exception('касса не найден');
        }
        $response = Http::withBasicAuth('Bearer',config('services.webkassa.key'))
            ->post(config('services.webkassa.server').'XReport',[
                'Token' => $token,
                'CashboxUniqueNumber' => $user->webkassaCashBox->unique_number,
             ]);

        if ($response->status() != 200){
            throw new Exception($response->json());
        }
        $data = $response->json();
        if (isset($data['Data'])){
            return $data['Data'];
        }
        if (isset($data['Errors'])){
            throw new Exception($data['Errors'][0]['Text']);
        }
        throw new Exception('response data not found');
    }



}
