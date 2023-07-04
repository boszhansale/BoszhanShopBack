<?php
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BasketController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CounteragentController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\MainController;
use App\Http\Controllers\Admin\MovingController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReceiptController;
use App\Http\Controllers\Admin\RefundController;
use App\Http\Controllers\Admin\RefundProducerController;
use App\Http\Controllers\Admin\RejectController;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('/', [AuthController::class, 'auth'])->name('auth');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware(['admin.check','auth:sanctum'])->group(function (){

    Route::get('main', [MainController::class, 'index'])->name('main');

    Route::prefix('product')->name('product.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('create', [ProductController::class, 'create'])->name('create');
        Route::post('store', [ProductController::class, 'store'])->name('store');
        Route::get('edit/{product}', [ProductController::class, 'edit'])->name('edit');
        Route::get('show/{product}', [ProductController::class, 'show'])->name('show');
        Route::put('update/{product}', [ProductController::class, 'update'])->name('update');
        Route::get('delete/{product}', [ProductController::class, 'delete'])->name('delete');
        Route::get('deleteImage/{productImage}', [ProductController::class, 'deleteImage'])->name('deleteImage');

        Route::post('counteragent-price/{product}', [ProductController::class, 'counteragentPriceStore'])->name(
            'counteragentPriceStore'
        );
        Route::get('counteragent-price/{counteragentPrice}', [ProductController::class, 'counteragentPriceDelete'])->name(
            'counteragentPriceDelete'
        );

        Route::post('barcode/store/{product}', [ProductController::class, 'barcodeCreate'])->name('barcode.store');
        Route::get('barcode/delete/{productBarcode}', [ProductController::class, 'barcodeDelete'])->name('barcode.delete');
    });

    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('create', [UserController::class, 'create'])->name('create');
        Route::post('store', [UserController::class, 'store'])->name('store');
        Route::get('edit/{user}', [UserController::class, 'edit'])->name('edit');
        Route::get('show/{user}', [UserController::class, 'show'])->name('show');
        Route::put('update/{user}', [UserController::class, 'update'])->name('update');
        Route::get('delete/{user}', [UserController::class, 'delete'])->name('delete');
        Route::get('position/{user}', [UserController::class, 'position'])->name('position');
    });
    Route::prefix('store')->name('store.')->group(function () {
        Route::get('/', [StoreController::class, 'index'])->name('index');
        Route::get('create', [StoreController::class, 'create'])->name('create');
        Route::post('store', [StoreController::class, 'store'])->name('store');
        Route::get('edit/{store}', [StoreController::class, 'edit'])->name('edit');
        Route::get('show/{store}', [StoreController::class, 'show'])->name('show');
        Route::put('update/{store}', [StoreController::class, 'update'])->name('update');
        Route::get('delete/{store}', [StoreController::class, 'delete'])->name('delete');
        Route::get('remove/{store}', [StoreController::class, 'remove'])->name('remove');
        Route::get('recover/{store}', [StoreController::class, 'recover'])->name('recover');
    });
    Route::prefix('counteragent')->name('counteragent.')->group(function () {
        Route::get('/', [CounteragentController::class, 'index'])->name('index');
        Route::get('create', [CounteragentController::class, 'create'])->name('create');
        Route::post('store', [CounteragentController::class, 'store'])->name('store');
        Route::get('edit/{counteragent}', [CounteragentController::class, 'edit'])->name('edit');
        Route::get('show/{counteragent}', [CounteragentController::class, 'show'])->name('show');
        Route::put('update/{counteragent}', [CounteragentController::class, 'update'])->name('update');
        Route::get('delete/{counteragent}', [CounteragentController::class, 'delete'])->name('delete');
        Route::get('order/{counteragent}', [CounteragentController::class, 'order'])->name('order');

        Route::get('import', [CounteragentController::class, 'import'])->name('import');
        Route::post('importing', [CounteragentController::class, 'importing'])->name('importing');
    });



    Route::prefix('order')->name('order.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('create', [OrderController::class, 'create'])->name('create');
        Route::post('store', [OrderController::class, 'store'])->name('store');
        Route::get('edit/{order}', [OrderController::class, 'edit'])->name('edit');
        Route::get('show/{order}', [OrderController::class, 'show'])->name('show');
        Route::put('update/{order}', [OrderController::class, 'update'])->name('update');
        Route::get('delete/{order}', [OrderController::class, 'delete'])->name('delete');
        Route::get('remove/{order}', [OrderController::class, 'remove'])->name('remove');
        Route::get('recover/{order}', [OrderController::class, 'recover'])->name('recover');
        Route::get('history/{order}', [OrderController::class, 'history'])->name('history');
    });



    Route::prefix('refund')->name('refund.')->group(function () {
        Route::get('/', [RefundController::class, 'index'])->name('index');
        Route::get('create', [RefundController::class, 'create'])->name('create');
        Route::post('store', [RefundController::class, 'store'])->name('store');
        Route::get('edit/{refund}', [RefundController::class, 'edit'])->name('edit');
        Route::get('show/{refund}', [RefundController::class, 'show'])->name('show');
        Route::put('update/{refund}', [RefundController::class, 'update'])->name('update');
        Route::get('delete/{refund}', [RefundController::class, 'delete'])->name('delete');
        Route::get('remove/{refund}', [RefundController::class, 'remove'])->name('remove');
        Route::get('recover/{refund}', [RefundController::class, 'recover'])->name('recover');
        Route::get('history/{refund}', [RefundController::class, 'history'])->name('history');
    });
    Route::prefix('refund-producer')->name('refundProducer.')->group(function () {
        Route::get('/', [RefundProducerController::class, 'index'])->name('index');
        Route::get('create', [RefundProducerController::class, 'create'])->name('create');
        Route::post('store', [RefundProducerController::class, 'store'])->name('store');
        Route::get('edit/{refundProducer}', [RefundProducerController::class, 'edit'])->name('edit');
        Route::get('show/{refundProducer}', [RefundProducerController::class, 'show'])->name('show');
        Route::put('update/{refundProducer}', [RefundProducerController::class, 'update'])->name('update');
        Route::get('delete/{refundProducer}', [RefundProducerController::class, 'delete'])->name('delete');
        Route::get('remove/{refundProducer}', [RefundProducerController::class, 'remove'])->name('remove');
        Route::get('recover/{refundProducer}', [RefundProducerController::class, 'recover'])->name('recover');
        Route::get('history/{refundProducer}', [RefundProducerController::class, 'history'])->name('history');
    });

    Route::prefix('receipt')->name('receipt.')->group(function () {
        Route::get('/', [ReceiptController::class, 'index'])->name('index');
        Route::get('create', [ReceiptController::class, 'create'])->name('create');
        Route::post('store', [ReceiptController::class, 'store'])->name('store');
        Route::get('edit/{receipt}', [ReceiptController::class, 'edit'])->name('edit');
        Route::get('show/{receipt}', [ReceiptController::class, 'show'])->name('show');
        Route::put('update/{receipt}', [ReceiptController::class, 'update'])->name('update');
        Route::get('delete/{receipt}', [ReceiptController::class, 'delete'])->name('delete');
        Route::get('remove/{receipt}', [ReceiptController::class, 'remove'])->name('remove');
        Route::get('recover/{receipt}', [ReceiptController::class, 'recover'])->name('recover');
        Route::get('history/{receipt}', [ReceiptController::class, 'history'])->name('history');
    });


    Route::prefix('moving')->name('moving.')->group(function () {
        Route::get('/', [MovingController::class, 'index'])->name('index');
        Route::get('create', [MovingController::class, 'create'])->name('create');
        Route::post('store', [MovingController::class, 'store'])->name('store');
        Route::get('edit/{moving}', [MovingController::class, 'edit'])->name('edit');
        Route::get('show/{moving}', [MovingController::class, 'show'])->name('show');
        Route::put('update/{moving}', [MovingController::class, 'update'])->name('update');
        Route::get('delete/{moving}', [MovingController::class, 'delete'])->name('delete');
        Route::get('remove/{moving}', [MovingController::class, 'remove'])->name('remove');
        Route::get('recover/{moving}', [MovingController::class, 'recover'])->name('recover');
        Route::get('history/{moving}', [MovingController::class, 'history'])->name('history');
    });

    Route::prefix('reject')->name('reject.')->group(function () {
        Route::get('/', [RejectController::class, 'index'])->name('index');
        Route::get('create', [RejectController::class, 'create'])->name('create');
        Route::post('store', [RejectController::class, 'store'])->name('store');
        Route::get('edit/{reject}', [RejectController::class, 'edit'])->name('edit');
        Route::get('show/{reject}', [RejectController::class, 'show'])->name('show');
        Route::put('update/{reject}', [RejectController::class, 'update'])->name('update');
        Route::get('delete/{reject}', [RejectController::class, 'delete'])->name('delete');
        Route::get('remove/{reject}', [RejectController::class, 'remove'])->name('remove');
        Route::get('recover/{reject}', [RejectController::class, 'recover'])->name('recover');
        Route::get('history/{reject}', [RejectController::class, 'history'])->name('history');
    });
    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::get('/', [InventoryController::class, 'index'])->name('index');
        Route::get('create', [InventoryController::class, 'create'])->name('create');
        Route::post('store', [InventoryController::class, 'store'])->name('store');
        Route::get('edit/{inventory}', [InventoryController::class, 'edit'])->name('edit');
        Route::get('show/{inventory}', [InventoryController::class, 'show'])->name('show');
        Route::put('update/{inventory}', [InventoryController::class, 'update'])->name('update');
        Route::get('delete/{inventory}', [InventoryController::class, 'delete'])->name('delete');
        Route::get('remove/{inventory}', [InventoryController::class, 'remove'])->name('remove');
        Route::get('recover/{inventory}', [InventoryController::class, 'recover'])->name('recover');
        Route::get('history/{inventory}', [InventoryController::class, 'history'])->name('history');
    });


});


