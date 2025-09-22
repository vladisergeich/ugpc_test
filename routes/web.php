<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EngravingOrderController;
use App\Http\Controllers\GravureDatabaseController;
use App\Http\Controllers\MacroOrderController;
use App\Http\Controllers\EngravingOrderShaftController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LayoutConstructorController;
use App\Http\Controllers\InputControlController;
use App\Http\Controllers\MovementOrderController;
use App\Http\Controllers\RouteMapController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\ShaftConsumptionController;
use App\Http\Controllers\OperationLedgerEntryController;
use App\Http\Controllers\DailyPlanController;
use App\Http\Controllers\DefectLedgerEntryController;
use App\Http\Controllers\MobileInputControlController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\SteelAppController;
use App\Http\Controllers\SteelAppShaftController;
use App\Http\Controllers\ShaftController;
use App\Http\Controllers\TransferShaftController;
use App\Http\Controllers\RegravationApprovalRequestController;
use App\Http\Controllers\ShaftResourceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FingerPrintController;
use App\Http\Controllers\PrintProcessController;
use App\Http\Controllers\MobileElectroPlatingController;
use Inertia\Inertia;


Route::middleware(['auth'])->prefix('gravuredatabase')->group(function () {
    Route::get('/getProfiles', [GravureDatabaseController::class, 'getProfiles'])->name('engravingOrders.getProfiles');
    Route::get('/getVendors', [GravureDatabaseController::class, 'getVendors'])->name('engravingOrders.getVendors');
    Route::get('/getDesigners', [GravureDatabaseController::class, 'getDesigners'])->name('engravingOrders.getDesigners');
    Route::get('/getCustomers', [GravureDatabaseController::class, 'getCustomers'])->name('engravingOrders.getCustomers');
    Route::get('/getEngravingOrderStatuses', [GravureDatabaseController::class, 'getEngravingOrderStatuses'])->name('engravingOrders.getEngravingOrderStatuses');
    Route::get('/getEngravingOrderConditions', [GravureDatabaseController::class, 'getEngravingOrderConditions'])->name('engravingOrders.getEngravingOrderConditions');
    Route::get('/getMountingParameters', [GravureDatabaseController::class, 'getMountingParameters'])->name('engravingOrders.getMountingParameters');
    Route::get('/getMacro', [GravureDatabaseController::class, 'getMacro'])->name('gravuredatabase.getMacro');
});

Route::middleware(['auth'])->prefix('macro-orders')->group(function () {
    Route::get('/', [MacroOrderController::class, 'index'])->name('macroOrders.index');
    Route::get('/{macroOrder}', [MacroOrderController::class, 'show'])->name('macroOrders.show');
    Route::get('/{macroOrder}/engraving-orders/{engravingOrder}', [EngravingOrderController::class, 'show'])->name('engravingOrders.show');
    Route::post('/create', [MacroOrderController::class, 'create'])->name('macroOrders.create');
});

Route::middleware(['auth'])->prefix('engraving-orders')->group(function () {
    Route::post('/update', [EngravingOrderController::class, 'update'])->name('engravingOrders.update');
    Route::post('/create', [EngravingOrderController::class, 'create'])->name('engravingOrders.create');
    Route::post('/sendApplication', [EngravingOrderController::class, 'sendApplication'])->name('engravingOrders.sendApplication');
    Route::post('/sendShaftInfo', [EngravingOrderController::class, 'sendShaftInfo'])->name('engravingOrders.sendShaftInfo');
    Route::post('/sendStreamInfo', [EngravingOrderController::class, 'sendStreamInfo'])->name('engravingOrders.sendStreamInfo');
    Route::get('/search', [EngravingOrderController::class, 'search'])->name('engravingOrders.search');
});

Route::middleware(['auth'])->prefix('orders')->group(function () {
    Route::post('/saveOrder', [OrderController::class, 'saveOrder'])->name('order.saveOrder');
});

Route::middleware(['auth'])->prefix('engraving-order-shafts')->group(function () {
    Route::post('/update', [EngravingOrderShaftController::class, 'update'])->name('engravingOrderShafts.update');
    Route::post('/create', [EngravingOrderShaftController::class, 'create'])->name('engravingOrderShafts.create');
    Route::post('/destroy', [EngravingOrderShaftController::class, 'destroy'])->name('engravingOrderShafts.destroy');
    Route::get('/getFreeShafts', [EngravingOrderShaftController::class, 'getFreeShafts'])->name('engravingOrderShafts.getFreeShafts');
    Route::post('/returnShaft', [EngravingOrderShaftController::class, 'returnShaft'])->name('engravingOrderShafts.returnShaft');
});

Route::middleware(['auth'])->prefix('shafts')->group(function () {
    Route::post('/update', [ShaftController::class, 'update'])->name('shafts.update');
});

Route::middleware(['auth'])->prefix('register')->group(function () {
    Route::get('/', fn () => redirect()->route('register.shafts'));
    Route::get('/shafts', [RegisterController::class, 'shafts'])->name('register.shafts');
    Route::get('/orderShafts', [RegisterController::class, 'orderShafts'])->name('register.orderShafts');
    Route::get('/items', [RegisterController::class, 'items'])->name('register.items');
    Route::get('/formats', [RegisterController::class, 'formats'])->name('register.formats');
    Route::get('/getHistory', [RegisterController::class, 'getHistory'])->name('register.getHistory');
});

Route::middleware(['auth'])->prefix('layout-constructor')->group(function () {
    Route::post('/create', [LayoutConstructorController::class, 'create'])->name('layoutConstructor.create');
    Route::post('/destroy', [LayoutConstructorController::class, 'destroy'])->name('layoutConstructor.destroy');
    Route::post('/update', [LayoutConstructorController::class, 'update'])->name('layoutConstructor.update');
});

Route::middleware(['auth'])->prefix('route-map')->group(function () {
    Route::get('/', [RouteMapController::class, 'index'])->name('routeMap.index');
    Route::get('/{engravingOrderShaft}', [RouteMapController::class, 'show'])->name('routeMap.show');
    Route::post('/alterationStages', [RouteMapController::class, 'alterationStages'])->name('routeMap.alterationStages');
    Route::post('/skipDefect', [RouteMapController::class, 'skipDefect'])->name('routeMap.skipDefect');
    Route::post('/replaceShaft', [RouteMapController::class, 'replaceShaft'])->name('routeMap.replaceShaft');
});

Route::middleware(['auth'])->prefix('input-control')->group(function () {
    Route::get('/', [InputControlController::class, 'index'])->name('inputControl.index');
    Route::post('/infoshaft', [InputControlController::class, 'infoShaft'])->name('inputControl.infoShaft');
    Route::post('/acceptshaft',[InputControlController::class, 'acceptShaft'])->name('inputControl.acceptShaft');
});

Route::middleware(['auth'])->prefix('movement-order')->group(function () {
    Route::get('/', [MovementOrderController::class, 'index'])->name('movementOrder.index');
    Route::post('/update', [MovementOrderController::class, 'update'])->name('movementOrder.update');
    Route::get('/getOrders', [MovementOrderController::class, 'getOrders'])->name('movementOrder.getOrders');
    Route::post('/redistributeOrders', [MovementOrderController::class, 'redistributeOrders'])->name('movementOrder.redistributeOrders');
    Route::post('/addDownTime', [MovementOrderController::class, 'addDownTime'])->name('movementOrder.addDownTime');
    Route::post('/split', [MovementOrderController::class, 'splitOrder'])->name('movementOrder.split');
    Route::post('/addDownTime', [MovementOrderController::class, 'addDownTime'])->name('movementOrder.addDownTime');
});

Route::middleware(['auth'])->prefix('machine')->group(function () {
    Route::get('/', [MachineController::class, 'index'])->name('machine.index');
    Route::get('/{machine}', [MachineController::class, 'show'])->name('machine.show');
});

Route::middleware(['auth'])->prefix('shaftConsump')->group(function () {
    Route::post('/create', [ShaftConsumptionController::class, 'create'])->name('machine.create');
    Route::post('/destroy', [ShaftConsumptionController::class, 'destroy'])->name('machine.destroy');
});

Route::middleware(['auth'])->prefix('defect')->group(function () {
    Route::post('/create', [DefectLedgerEntryController::class, 'create'])->name('defect.create');
    Route::post('/destroy', [DefectLedgerEntryController::class, 'destroy'])->name('defect.destroy');
});

Route::middleware(['auth'])->prefix('operationLedgerEntry')->group(function () {
    Route::post('/create', [OperationLedgerEntryController::class, 'create'])->name('operationLedgerEntry.create');
    Route::post('/update', [OperationLedgerEntryController::class, 'update'])->name('operationLedgerEntry.update');
    Route::post('/destroy', [OperationLedgerEntryController::class, 'destroy'])->name('operationLedgerEntry.destroy');
});

Route::middleware(['auth'])->prefix('dailyPlan')->group(function () {
    Route::get('/', fn () => redirect()->route('dailyPlan.statistic'));
    Route::get('/statistic', [DailyPlanController::class, 'statistic'])->name('dailyPlan.statistic');
    Route::get('/tablePlan', [DailyPlanController::class, 'tablePlan'])->name('dailyPlan.tablePlan');
});

Route::middleware(['auth'])->prefix('transfers')->group(function () {
    Route::get('/', [TransferController::class, 'index'])->name('transfers.index');
    Route::get('/{transfer}', [TransferController::class, 'show'])->name('transfers.show');
    Route::post('/create', [TransferController::class, 'create'])->name('transfers.create');
    Route::get('/transfers/getShafts', [TransferController::class, 'getShafts'])->name('transfers.getShafts');
    Route::post('/update', [TransferController::class, 'update'])->name('transfers.update');
    Route::get('/{id}/upak', [TransferController::class, 'upakList'])->name('transfers.upakList');
    Route::post('/confirmTransfer', [TransferController::class, 'confirmTransfer'])->name('transfers.confirmTransfer');
});

Route::middleware(['auth'])->prefix('transfer-shafts')->group(function () {
    Route::post('/create', [TransferShaftController::class, 'create'])->name('transferShafts.create');
    Route::post('/update', [TransferShaftController::class, 'update'])->name('transferShafts.update');
    Route::post('/destroy', [TransferShaftController::class, 'destroy'])->name('transferShafts.destroy');
});


Route::middleware(['auth'])->prefix('steelApp')->group(function () {
    Route::get('/', [SteelAppController::class, 'index'])->name('steelApp.index');
    Route::get('/{steelApp}', [SteelAppController::class, 'show'])->name('steelApp.show');
    Route::post('/create', [SteelAppController::class, 'create'])->name('steelApp.create');
    Route::post('/update', [SteelAppController::class, 'update'])->name('steelApp.update');
    Route::post('/upload', [SteelAppController::class, 'store'])->name('steelApp.store');
    Route::post('/deleteFile', [SteelAppController::class, 'deleteFile'])->name('steelApp.deleteFile');
    Route::post('/sendApp', [SteelAppController::class, 'sendApp'])->name('steelApp.sendApp');
});

Route::middleware(['auth'])->prefix('steelAppShafts')->group(function () {
    Route::post('/create', [SteelAppShaftController::class, 'create'])->name('steelAppShafts.create');
    Route::post('/destroy', [SteelAppShaftController::class, 'destroy'])->name('steelAppShafts.destroy');
    Route::post('/destroyAll', [SteelAppShaftController::class, 'destroyAll'])->name('steelAppShafts.destroyAll');
});

Route::middleware(['auth'])->prefix('regravation')->group(function () {
    Route::get('/', [RegravationApprovalRequestController::class, 'index'])->name('regravation.index');
    Route::post('/create', [RegravationApprovalRequestController::class, 'create'])->name('regravation.create');
});

Route::middleware(['auth'])->prefix('shaft-resource')->group(function () {
    Route::post('/create', [ShaftResourceController::class, 'create'])->name('shaftResource.create');
    Route::post('/destroy', [ShaftResourceController::class, 'destroy'])->name('shaftResource.destroy');
});

Route::middleware(['auth'])->prefix('profile')->group(function () {
    Route::get('/', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/{profile}', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/create', [ProfileController::class, 'create'])->name('profile.create');
    Route::post('/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/getProfiles', [ProfileController::class, 'getProfiles'])->name('profile.getProfiles');
    Route::post('/sendToNav', [ProfileController::class, 'sendToNav'])->name('profile.sendToNav');
});

Route::middleware(['auth'])->prefix('profileFingerPrint')->group(function () {
    Route::post('/create', [FingerPrintController::class, 'create'])->name('profileFingerPrint.create');
    Route::post('/update', [FingerPrintController::class, 'update'])->name('profileFingerPrint.update');
    Route::post('/destroy', [FingerPrintController::class, 'destroy'])->name('profileFingerPrint.destroy');
});

Route::middleware(['auth'])->prefix('profilePrintProcess')->group(function () {
    Route::post('/create', [PrintProcessController::class, 'create'])->name('profilePrintProcess.create');
    Route::post('/update', [PrintProcessController::class, 'update'])->name('profilePrintProcess.update');
    Route::post('/destroy', [PrintProcessController::class, 'destroy'])->name('profilePrintProcess.destroy');
});



Route::middleware(['auth'])->prefix('mobile/input-control')->group(function () {
    Route::get('/', [MobileInputControlController::class, 'index'])->name('mobileInputControl.index');
});

Route::middleware(['auth'])->prefix('mobile/electro-plating')->group(function () {
    Route::get('/', [MobileElectroPlatingController::class, 'index'])->name('mobileElectroPlating.index');
    Route::post('/infoshaft', [MobileElectroPlatingController::class, 'infoShaft'])->name('mobileElectroPlating.infoShaft');
});

Route::middleware(['auth'])->prefix('statistic')->group(function () {
    Route::get('/', [App\Http\Controllers\StatisticController::class, 'index'])->name('statistic.index');
    Route::get('/getData', [App\Http\Controllers\StatisticController::class, 'getData'])->name('statistic.getData');
    Route::get('/getWorkCenters', [App\Http\Controllers\StatisticController::class, 'getWorkCenters'])->name('statistic.getWorkCenters');
    Route::get('/getOperations', [App\Http\Controllers\StatisticController::class, 'getOperations'])->name('statistic.getOperations');
    Route::get('/getOperatorData', [App\Http\Controllers\StatisticController::class, 'getOperatorData'])->name('statistic.getOperatorData');
    Route::get('/getShiftData', [App\Http\Controllers\StatisticController::class, 'getShiftData'])->name('statistic.getShiftData');
});




