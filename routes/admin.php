<?php
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BasketController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CounteragentController;
use App\Http\Controllers\Admin\MainController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
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
        Route::get('drivers', [UserController::class, 'drivers'])->name('drivers');
        Route::get('salesreps', [UserController::class, 'salesreps'])->name('salesreps');
        Route::get('create/{roleId}', [UserController::class, 'create'])->name('create');
        Route::post('store', [UserController::class, 'store'])->name('store');
        Route::get('edit/{user}', [UserController::class, 'edit'])->name('edit');
        Route::get('show/{user}', [UserController::class, 'show'])->name('show');
        Route::put('update/{user}', [UserController::class, 'update'])->name('update');
        Route::get('delete/{user}', [UserController::class, 'delete'])->name('delete');
        Route::get('position/{user}', [UserController::class, 'position'])->name('position');
        Route::get('order/{user}/{role}', [UserController::class, 'order'])->name('order');
        Route::post('statistic/by-order-excel', [UserController::class, 'statisticByOrderExcel'])->name('statisticByOrderExcel');
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
        Route::get('order/{store}', [StoreController::class, 'order'])->name('order');

        Route::get('salesrep-move', [StoreController::class, 'salesrepMove'])->name('salesrep-move');
        Route::post('salesrep-moving', [StoreController::class, 'salesrepMoving'])->name('salesrep-moving');

        Route::get('driver-move', [StoreController::class, 'driverMove'])->name('driver-move');
        Route::post('driver-moving', [StoreController::class, 'driverMoving'])->name('driver-moving');

        Route::get('position/{user}', [StoreController::class, 'position'])->name('position');
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

    Route::prefix('brand')->name('brand.')->group(function () {
        Route::get('/', [BrandController::class, 'index'])->name('index');
        Route::get('create', [BrandController::class, 'create'])->name('create');
        Route::post('store', [BrandController::class, 'store'])->name('store');
        Route::get('edit/{brand}', [BrandController::class, 'edit'])->name('edit');
        Route::put('update/{brand}', [BrandController::class, 'update'])->name('update');
        Route::get('delete/{brand}', [BrandController::class, 'delete'])->name('delete');
    });
    Route::prefix('mobile-app')->name('mobile-app.')->group(function () {
        Route::get('/', [MobileAppController::class, 'index'])->name('index');
        Route::get('create/{type}', [MobileAppController::class, 'create'])->name('create');
        Route::post('store', [MobileAppController::class, 'store'])->name('store');

        Route::get('edit/{mobileApp}', [MobileAppController::class, 'edit'])->name('edit');
        Route::post('update/{mobileApp}', [MobileAppController::class, 'update'])->name('update');

        Route::get('delete/{mobileApp}', [MobileAppController::class, 'delete'])->name('delete');
        Route::get('download/{mobileApp}', [MobileAppController::class, 'download'])->name('download')->withoutMiddleware(
            'auth'
        );
    });
    Route::prefix('category')->name('category.')->group(function () {
        Route::get('/{brand}', [CategoryController::class, 'index'])->name('index');
        Route::get('create/{brand}', [CategoryController::class, 'create'])->name('create');
        Route::post('store/{brand}', [CategoryController::class, 'store'])->name('store');
        Route::get('edit/{category}', [CategoryController::class, 'edit'])->name('edit');
        Route::put('update/{category}', [CategoryController::class, 'update'])->name('update');
        Route::get('delete/{category}', [CategoryController::class, 'delete'])->name('delete');
    });

    Route::prefix('role')->name('role.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('create/', [RoleController::class, 'create'])->name('create');
        Route::post('store', [RoleController::class, 'store'])->name('store');
        Route::get('edit/{role}', [RoleController::class, 'edit'])->name('edit');
        Route::put('update/{role}', [RoleController::class, 'update'])->name('update');
        Route::get('delete/{role}', [RoleController::class, 'delete'])->name('delete');
    });

    Route::prefix('order')->name('order.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('to-onec/{id?}', [OrderController::class, 'toOnec'])->name('to-onec');
        Route::get('to-onec/edi', [OrderController::class, 'toOnecEdi'])->name('to-onec-edi');
        Route::get('create', [OrderController::class, 'create'])->name('create');
        Route::post('store', [OrderController::class, 'store'])->name('store');
        Route::get('edit/{order}', [OrderController::class, 'edit'])->name('edit');
        Route::get('many-edit', [OrderController::class, 'manyEdit'])->name('many-edit');
        Route::put('many-update', [OrderController::class, 'manyUpdate'])->name('many-update');
        Route::get('show/{order}', [OrderController::class, 'show'])->name('show');
        Route::put('update/{order}', [OrderController::class, 'update'])->name('update');
        Route::get('delete/{order}', [OrderController::class, 'delete'])->name('delete');
        Route::get('remove/{order}', [OrderController::class, 'remove'])->name('remove');
        Route::get('recover/{order}', [OrderController::class, 'recover'])->name('recover');
        Route::get('export-excel/{order}', [OrderController::class, 'exportExcel'])->name('export-excel')->withoutMiddleware(['auth', 'admin']);
        Route::get('waybill/{order}', [OrderController::class, 'waybill'])->name('waybill')->withoutMiddleware(['auth', 'admin']);;
        Route::get('driver-move', [OrderController::class, 'driverMove'])->name('driver-move');
        Route::post('driver-moving', [OrderController::class, 'driverMoving'])->name('driver-moving');
        Route::get('statistic', [OrderController::class, 'statistic'])->name('statistic');
        Route::get('history/{order}', [OrderController::class, 'history'])->name('history');
        Route::get('initial-state/{order}', [OrderController::class, 'initialState'])->name('initial-state');
        Route::get('edi/parse', [OrderController::class, 'ediParse'])->name('edi-parse');
        Route::get('edi', [OrderController::class, 'edi'])->name('edi');
    });
    Route::prefix('basket')->name('basket.')->group(function () {
        Route::get('create/{order}/{type}', [BasketController::class, 'create'])->name('create');
        Route::post('store/{order}', [BasketController::class, 'store'])->name('store');
        Route::get('edit/{basket}', [BasketController::class, 'edit'])->name('edit');
        Route::put('update/{basket}', [BasketController::class, 'update'])->name('update');
        Route::get('delete/{basket}', [BasketController::class, 'delete'])->name('delete');
    });
    Route::prefix('plan-group')->name('plan-group.')->group(function () {
        Route::get('index/', [PlanGroupController::class, 'index'])->name('index');
        Route::get('create/', [PlanGroupController::class, 'create'])->name('create');
        Route::post('store/', [PlanGroupController::class, 'store'])->name('store');
        Route::get('edit/{planGroup}', [PlanGroupController::class, 'edit'])->name('edit');
        Route::put('update/{planGroup}', [PlanGroupController::class, 'update'])->name('update');
        Route::get('delete/{planGroup}', [PlanGroupController::class, 'delete'])->name('delete');
    });

    Route::prefix('game')->name('game.')->group(function () {
        Route::get('/', [GameController::class, 'index'])->name('index');
        Route::get('edit/{game}', [GameController::class, 'edit'])->name('edit');
        Route::get('update/{game}', [GameController::class, 'update'])->name('update');
        Route::get('delete/{game}', [GameController::class, 'delete'])->name('delete');
    });

    Route::prefix('counteragent-group')->name('counteragent-group.')->group(function () {
        Route::get('/', [CounteragentGroupController::class, 'index'])->name('index');
        Route::get('create', [CounteragentGroupController::class, 'create'])->name('create');
        Route::post('/', [CounteragentGroupController::class, 'store'])->name('store');
        Route::get('edit/{counteragentGroup}', [CounteragentGroupController::class, 'edit'])->name('edit');
        Route::put('update/{counteragentGroup}', [CounteragentGroupController::class, 'update'])->name('update');
        Route::get('delete/{counteragentGroup}', [CounteragentGroupController::class, 'delete'])->name('delete');
    });

});


