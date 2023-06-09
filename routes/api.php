<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CounteragentController;
use App\Http\Controllers\Api\InventoryController;
use App\Http\Controllers\Api\MovingController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ReceiptController;
use App\Http\Controllers\Api\RefundController;
use App\Http\Controllers\Api\RefundProducerController;
use App\Http\Controllers\Api\RejectController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\StorageController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WebkassaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('brand',[BrandController::class,'index']);
Route::get('category',[CategoryController::class,'index']);
Route::get('product',[ProductController::class,'index']);
Route::get('storage',[StorageController::class,'index']);




Route::prefix('auth')->group(function (){
   Route::post('login',[AuthController::class,'login']);
   Route::post('logout',[AuthController::class,'logout'])->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function (){
    Route::prefix('webkassa')->group(function (){
        Route::post('get-token',[WebkassaController::class,'getToken']);
        Route::post('money-operation',[WebkassaController::class,'moneyOperation']);
        Route::post('z-report',[WebkassaController::class,'ZReport']);
        Route::post('x-report',[WebkassaController::class,'XReport']);
    });

    Route::prefix('user')->group(function (){
        Route::get('/',[UserController::class,'profile']);
        Route::post('update',[UserController::class,'update']);
    });

    Route::prefix('counteragent')->group(function (){
        Route::get('/',[CounteragentController::class,'index']);
        Route::get('all',[CounteragentController::class,'all']);
    });
    //Продажа
    Route::prefix('order')->group(function (){
        Route::get('/',[OrderController::class,'index']);
        Route::get('history',[OrderController::class,'history']);
        Route::post('/',[OrderController::class,'store']);
        Route::post('update/{order}',[OrderController::class,'update']);
        Route::delete('{order}',[OrderController::class,'delete']);
        Route::post('check/{order}',[OrderController::class,'check']);
        Route::get('print-check/{order}',[OrderController::class,'printCheck']);
    });
    //Возврат от покупателя
    Route::prefix('refund')->group(function (){
        Route::get('/',[RefundController::class,'index']);
        Route::get('history',[RefundController::class,'history']);
        Route::post('/',[RefundController::class,'store']);
        Route::delete('{refund}',[RefundController::class,'delete']);
        Route::post('check/{refund}',[RefundController::class,'check']);
        Route::get('print-check/{refund}',[RefundController::class,'printCheck']);
    });
    //Возврат товара поставщику
    Route::prefix('refund-producer')->group(function (){
        Route::get('/',[RefundProducerController::class,'index']);
        Route::get('history',[RefundProducerController::class,'history']);
        Route::post('/',[RefundProducerController::class,'store']);
        Route::delete('{refundProducer}',[RefundProducerController::class,'delete']);
        Route::post('check/{refundProducer}',[RefundProducerController::class,'check']);
        Route::get('print-check/{refundProducer}',[RefundProducerController::class,'printCheck']);
    });
    //Поступление товара
    Route::prefix('receipt')->group(function (){
        Route::get('/',[ReceiptController::class,'index']);
        Route::get('history',[ReceiptController::class,'history']);
        Route::post('/',[ReceiptController::class,'store']);
        Route::delete('{receipt}',[ReceiptController::class,'delete']);
        Route::post('check/{receipt}',[ReceiptController::class,'check']);
        Route::get('print-check/{receipt}',[ReceiptController::class,'printCheck']);
    });
    //Перемещения товара
    Route::prefix('moving')->group(function (){
        Route::get('/',[MovingController::class,'index']);
        Route::get('history',[MovingController::class,'history']);
        Route::post('/',[MovingController::class,'store']);
        Route::delete('{moving}',[MovingController::class,'delete']);
        Route::post('check/{moving}',[MovingController::class,'check']);
    });
    //Списание
    Route::prefix('reject')->group(function (){
        Route::get('/',[RejectController::class,'index']);
        Route::get('history',[RejectController::class,'history']);
        Route::post('/',[RejectController::class,'store']);
        Route::delete('{reject}',[RejectController::class,'delete']);
    });
    Route::prefix('inventory')->group(function (){
        Route::get('/',[InventoryController::class,'index']);
        Route::post('/',[InventoryController::class,'store']);
        Route::post('add-receipt',[InventoryController::class,'addReceipt']);
        Route::get('history',[InventoryController::class,'history']);
    });
    Route::prefix('report')->group(function (){
        Route::get('remains',[ReportController::class,'remains']);
        Route::get('discount-card',[ReportController::class,'discountCard']);
        Route::get('order',[ReportController::class,'order']);
        Route::get('inventor',[ReportController::class,'inventor']);
        Route::get('product',[ReportController::class,'product']);
    });
});
