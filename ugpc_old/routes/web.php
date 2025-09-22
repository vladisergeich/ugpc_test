<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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



Danaflex\KeycloakWebGuard\Facades\KeycloakWeb::routes();

Route::middleware(['auth'])->group(function () {
    //Route::post('constants', 'PortalConstantController@createOrUpdateConstant')->name('constants.manage');
    //Route::get('users/search', 'UserSearchController@userSearch')->name('user.search');
    //oute::get('projectObjectives/search', 'UserSearchController@projectObjectiveSearch')->name('project.objectives.search');
    //Route::get('global/', 'GlobalController@getDataFromModel')->name('global.getmodeldata');
    //Route::get('global/search', 'SearchController@search')->name('global.search');
  });


Route::middleware(['auth','role:okvid'])->group(function () {

    Route::get('/', function () {

      return redirect(
        'https://portal.danaflex.ru/profile'
      );

    })->name('main');


    //Route::namespace('Ugpc')->prefix('ugpc')->middleware(['auth'])->group(function () {
    Route::get('/ugpc/approval/', 'UgpcApprovalController@index')->name('ugpc.approval');

    Route::get('/ugpc/technologist/', 'UgpcTechnologistController@index')->name('ugpc.technologist');

    Route::get('/ugpc/bdgp/', 'BdgpController@index')->name('ugpc.bdgp');

    Route::get('/ugpc/bdgp/ordercard/{macroorder}', 'OrderCardController@index')->name('ugpc.bdgp.ordercard');

    Route::post('/ugpc/bdgp/update/', 'BdgpController@update')->name('ugpc.bdgp.update');
    Route::post('/ugpc/bdgp/shafts/update', 'BdgpController@updateShafts')->name('ugpc.bdgp.shafts.update');
    Route::get('/ugpc/bdgp/createdouble', 'BdgpController@createdouble')->name('ugpc.bdgp.double');
    Route::get('/ugpc/gethandbookdataval', 'BdgpController@getHandBookDataVal')->name('ugpc.gethandbookdataval');
    Route::post('/ugpc/submitshaftbatch', 'BdgpController@submitShaftBatch')->name('ugpc.submitshaftbatch');
    Route::get('/ugpc/createstreamxml/', 'BdgpController@createStreamXml')->name('ugpc.bdgp.createstreamxml');
    Route::get('/ugpc/createshaftsxml/', 'BdgpController@createShaftsXml')->name('ugpc.bdgp.createshaftsxml');
    Route::get('/ugpc/createnavxml/', 'BdgpController@createToNavXml')->name('ugpc.createnavxml');
    Route::get('/ugpc/getokvidcard/', 'BdgpController@getOkvidCard')->name('ugpc.getokvidcard');

    Route::get('/ugpc/getshafts', 'BdgpController@getShafts')->name('ugpc.getshaftsorders');
    Route::post('/ugpc/submitshaft', 'OrderCardController@submitShaft')->name('ugpc.submitshaft');
    Route::get('/ugpc/invoices/download', 'BdgpController@downloadPdf')->name('ugpc.bdgp.invoices.download');

    //OrdrCard
    Route::get('/ugpc/deletestream/', 'OrderCardController@deleteStream')->name('ugpc.deletestream');
    Route::get('/ugpc/deleteshaft/', 'OrderCardController@deleteShaft')->name('ugpc.deleteshaft');
    Route::get('/ugpc/freeshaft/', 'OrderCardController@freeShaft')->name('ugpc.freeshaft');
    Route::get('/ugpc/backshaft/', 'OrderCardController@backShaft')->name('ugpc.backshaft');
    Route::get('/ugpc/deletegp/', 'OrderCardController@deleteGp')->name('ugpc.deletegp');
    Route::get('/ugpc/getshafts/', 'OrderCardController@getShafts')->name('ugpc.getshafts');
    Route::get('/ugpc/addstream/', 'OrderCardController@addStream')->name('ugpc.addstream');
    Route::get('/ugpc/getlayout/', 'OrderCardController@getLayout')->name('ugpc.getlayout');
    Route::post('/ugpc/submitstream/', 'OrderCardController@submitStream')->name('ugpc.submitstream');
    Route::get('/ugpc/getstreams', 'OrderCardController@getStreams')->name('ugpc.getstreams');
    Route::get('/ugpc/addgp/', 'OrderCardController@addGp')->name('ugpc.addgp');
    Route::get('/ugpc/addshaft/', 'OrderCardController@addShaft')->name('ugpc.addshaft');
    Route::get('/ugpc/shaftresource/', 'OrderCardController@shaftResource')->name('ugpc.shaftresource');
    Route::get('/ugpc/shafttransfer/', 'OrderCardController@shaftTransfer')->name('ugpc.shafttransfer');
    Route::get('/ugpc/createroutemap/', 'OrderCardController@createRouteMap')->name('ugpc.createroutemap');
    Route::get('/ugpc/checknavparam/', 'OrderCardController@checkNavParam')->name('ugpc.checknavparam');
    Route::get('/ugpc/addshaftresource', 'OrderCardController@addShaftResource')->name('ugpc.addshaftresource');
    Route::get('/ugpc/getshaftresource', 'OrderCardController@getShaftResource')->name('ugpc.getshaftresource');
    Route::post('/ugpc/submitresource', 'OrderCardController@SubmitResource')->name('ugpc.submitresource');
    Route::get('/ugpc/deleteshaftresource', 'OrderCardController@deleteShaftResource')->name('ugpc.deleteshaftresource');
    Route::get('/ugpc/scpreset/', 'OrderCardController@scPreset')->name('ugpc.scpreset');
    Route::get('/ugpc/rmpreset/', 'OrderCardController@rmPreset')->name('ugpc.rmpreset');
    Route::get('/ugpc/nextokvid/', 'OrderCardController@nextOkvid')->name('ugpc.nextokvid');
    Route::get('/ugpc/bdgp/addsection', 'OrderCardController@addSection')->name('ugpc.bdgp.addsection');
    Route::get('/ugpc/bdgp/searchorder', 'OrderCardController@searchOrder')->name('ugpc.bdgp.searchorder');
    Route::get('/ugpc/bdgp/searchgp', 'OrderCardController@searchGp')->name('ugpc.bdgp.searchgp');
    Route::get('/ugpc/bdgp/searchshaft', 'OrderCardController@searchShaft')->name('ugpc.bdgp.searchshaft');
    Route::get('/ugpc/bdgp/copyshaftparametrs', 'OrderCardController@copyShaftParametrs')->name('ugpc.copyshaftparametrs');
    Route::post('/ugpc/bdgp/autoapproval/', 'OrderCardController@autoApproval')->name('ugpc.bdgp.autoapproval');


    //OrdrCard

    //ProfileRegistry
    Route::get('/ugpc/profileregistry/flexo', 'ShowFlexoProfileRegistry')->name('cma.profileregistry.flexo');;
    Route::get('/ugpc/profileregistry/roto', 'ShowRotoProfileRegistry');
    Route::get('/ugpc/profileregistry/profilecard/{id}', 'ProfileRegistry@profileCard')->name('ugpc.profileregistry.profilecard');
    Route::post('/ugpc/profileregistry/getprofiles', 'ProfileRegistry@getProfiles')->name('ugpc.profileregistry.getprofiles');
    Route::post('/ugpc/profileregistry/addnewprofile', 'ProfileRegistry@addNewProfile')->name('ugpc.profileregistry.addnewprofile');
    Route::post('/ugpc/profileregistry/addrow', 'ProfileRegistry@addRow')->name('ugpc.profileregistry.addrow');
    Route::post('/ugpc/profileregistry/deleterow', 'ProfileRegistry@deleteRow')->name('ugpc.profileregistry.deleterow');
    Route::post('/ugpc/profileregistry/saveprofile', 'ProfileRegistry@saveProfile')->name('ugpc.profileregistry.saveprofile');
    Route::post('/ugpc/profileregistry/copyprofile', 'ProfileRegistry@copyProfile')->name('ugpc.profileregistry.copyprofile');
    Route::post('/ugpc/profileregistry/senddatatonav', 'ProfileRegistry@sendDataToNav')->name('ugpc.profileregistry.senddatatonav');
    //ProfileRegistry

    //OkvidCard
    Route::get('/ugpc/okvidcard/', 'OkvidCardController@index')->name('ugpc.okvidcard');
    Route::get('/ugpc/getokvid', 'OkvidCardController@getOkvid')->name('ugpc.getokvid');
    Route::get('/ugpc/addokvid', 'OkvidCardController@addOkvid')->name('ugpc.addokvid');
    Route::post('/ugpc/bdgp/updateokvid', 'OkvidCardController@updateOkvid')->name('ugpc.bdgp.update.okvid');
    //OkvidCard

    //Handbook
    Route::get('/ugpc/handbook/', 'HandBookController@index')->name('ugpc.handbook');
    Route::get('/ugpc/gethandbookdatagp/', 'HandBookController@getHandBookDataGp')->name('ugpc.gethandbookdatagp');
    Route::get('/ugpc/gethandbookdataval/', 'HandBookController@getHandBookDataVal')->name('ugpc.gethandbookdataval');
    Route::get('/ugpc/getproductinfo/', 'HandBookController@getProductInfo')->name('ugpc.getproductinfo');
    Route::get('/ugpc/getshaftinfo/', 'HandBookController@getShaftInfo')->name('ugpc.getshaftinfo');
    Route::post('/ugpc/newshaft/', 'HandBookController@newShaft')->name('ugpc.newshaft');
    Route::post('/ugpc/newformat/', 'HandBookController@newFormat')->name('ugpc.newformat');
    Route::post('/ugpc/newcustomer/', 'HandBookController@newCustomer')->name('ugpc.newcustomer');
    Route::post('/ugpc/submitshaftnote/', 'HandBookController@submitShaftNote')->name('ugpc.submitshaftnote');
    Route::get('/ugpc/handbook/changedateorders/', 'HandBookController@changeDateOrders')->name('ugpc.handbook.changedateorders');
    //Handbook

    //Planning
    Route::get('/ugpc/planning/', 'PlanningController@index')->name('ugpc.planning');
    Route::get('/ugpc/planning/movementorders/', 'PlanningController@movementOrders')->name('ugpc.movementorders');
    Route::get('/ugpc/planning/unloadingorders/', 'PlanningController@unloadingOrders')->name('ugpc.unloadingorders');
    Route::post('/ugpc/planning/savemovementdata/', 'PlanningController@saveMovementData')->name('ugpc.movementorders.savemovementdata');
    Route::post('/ugpc/planning/savemovementdatarow/', 'PlanningController@saveMovementDataRow')->name('ugpc.movementorders.savemovementdatarow');
    Route::post('/ugpc/planning/savemovementdataprioruty/', 'PlanningController@saveMovementDataPriority')->name('ugpc.movementorders.savemovementdataprioruty');
    Route::post('/ugpc/planning/distributeorders/', 'PlanningController@distributeOrders')->name('ugpc.movementorders.distributeorders');
    Route::post('/ugpc/planning/okvidfulfilled/', 'PlanningController@okvidFulfilled')->name('ugpc.planning.okvidfulfilled');
    Route::post('/ugpc/planning/adddowntime/', 'PlanningController@addDowntime')->name('ugpc.movementorders.adddowntime');
    Route::post('/ugpc/planning/postmovementorders/', 'PlanningController@postMovementOrders')->name('ugpc.movementorders.postmovementorders');
    Route::post('/ugpc/planning/deleterow/', 'PlanningController@deleteRow')->name('ugpc.movementorders.deleterow');
    Route::post('/ugpc/planning/breakrow/', 'PlanningController@breakRow')->name('ugpc.movementorders.breakrow');
    Route::post('/ugpc/planning/backbreakrow/', 'PlanningController@backBreakrow')->name('ugpc.movementorders.backbreakrow');
    Route::post('/ugpc/planning/cancelchanges/', 'PlanningController@cancelChanges')->name('ugpc.movementorders.cancelchanges');
    Route::post('/ugpc/planning/dailydistribution/', 'PlanningController@dailyDistribution')->name('ugpc.movementorders.dailydistribution');
    Route::post('/ugpc/planning/deletedowntime/', 'PlanningController@deleteDowntime')->name('ugpc.movementorders.deletedowntime');
    Route::post('/ugpc/planning/saveplan/', 'PlanningController@savePlan')->name('ugpc.executionplan.saveplan');

    //Planning

    //Register
    Route::get('/ugpc/register/', 'RegisterController@index')->name('ugpc.register');
    //Register



    //RequestShaft
    Route::get('/ugpc/sendrequestshaft/', 'SendRequestShaftController@index')->name('ugpc.sendrequestshaft.list');
    Route::get('/ugpc/sendrequestshaftcard/{id}', 'SendRequestShaftController@requestCard')->name('ugpc.sendrequestshaft.card');
    Route::get('/ugpc/sendrequestshaft/getrequest', 'SendRequestShaftController@getRequest')->name('ugpc.sendrequestshaft.getrequest');
    Route::get('/ugpc/sendrequestshaft/getrequestcomposition', 'SendRequestShaftController@getRequestComposition')->name('ugpc.sendrequestshaft.getrequestcomposition');
    Route::post('/ugpc/sendrequestshaft/addrequestorder', 'SendRequestShaftController@addRequestOrder')->name('ugpc.sendrequestshaft.addrequestorder');
    Route::get('/ugpc/sendrequestshaft/deleterequestorder', 'SendRequestShaftController@deleteRequestOrder')->name('ugpc.sendrequestshaft.deleterequestorder');
    Route::get('/ugpc/sendrequestshaft/getokvid', 'SendRequestShaftController@getOkvid')->name('ugpc.sendrequestshaft.getokvid');
    Route::get('/ugpc/sendrequestshaft/sendpdf', 'SendRequestShaftController@sendPdf')->name('ugpc.sendrequestshaft.sendpdf');
    Route::get('/ugpc/sendrequestshaft/getpdf', 'SendRequestShaftController@getPdf')->name('ugpc.sendrequestshaft.getpdf');
    Route::get('/ugpc/sendrequestshaft/addrequest', 'SendRequestShaftController@addRequest')->name('ugpc.sendrequestshaft.addrequest');
    Route::post('/ugpc/submitrequestcomposition/', 'SendRequestShaftController@submitRequestComposition')->name('ugpc.sendrequestshaft.submitrequestcomposition');
    Route::post('/ugpc/submitrequest/', 'SendRequestShaftController@submitRequest')->name('ugpc.sendrequestshaft.submitrequest');
    Route::post('/ugpc/sendrequestshaft/getshaftwarehouse', 'SendRequestShaftController@getShaftWarehouse')->name('ugpc.sendrequestshaft.getshaftwarehouse');
    Route::get('/ugpc/sendrequestshaft/postshaftrequest', 'SendRequestShaftController@postShaftRequest')->name('ugpc.sendrequestshaft.postshaftrequest');
    Route::get('/ugpc/sendrequestshaft/confirmrequest', 'SendRequestShaftController@confirmRequest')->name('ugpc.sendrequestshaft.confirmrequest');
    Route::get('/ugpc/sendrequestshaft/updaterequest', 'SendRequestShaftController@updateRequest')->name('ugpc.sendrequestshaft.updaterequest');
    Route::post('/ugpc/sendrequestshaft/updateshaft', 'SendRequestShaftController@updateShaft')->name('ugpc.sendrequestshaft.updateshaft');
    //RequestShaft
    // });


    //AppSteelShaft
    Route::get('/ugpc/bdgp/appsteel', 'ApplicationSteelShaftController@index')->name('ugpc.bdgp.appsteel');
    Route::post('/ugpc/bdgp/appsteel/addapplication', 'ApplicationSteelShaftController@addApplication')->name('ugpc.bdgp.appsteel.addapplication');
    Route::post('/ugpc/bdgp/appfsteel/getshaftslist', 'ApplicationSteelShaftController@getShaftsList')->name('ugpc.bdgp.appsteel.getshaftslist');
    Route::post('/ugpc/bdgp/appfsteel/deleteallshafts', 'ApplicationSteelShaftController@deleteAllShafts')->name('ugpc.bdgp.appsteel.deleteallshafts');
    Route::post('/ugpc/bdgp/appfsteel/addshafts', 'ApplicationSteelShaftController@addShafts')->name('ugpc.bdgp.appsteel.addshafts');
    Route::post('/ugpc/bdgp/appfsteel/deleteshaft', 'ApplicationSteelShaftController@deleteShaft')->name('ugpc.bdgp.appsteel.deleteshaft');
    Route::post('/ugpc/bdgp/appfsteel/submitapp', 'ApplicationSteelShaftController@submitApp')->name('ugpc.bdgp.appsteel.submitapp');
    Route::post('/ugpc/bdgp/appfsteel/uploadfiles', 'ApplicationSteelShaftController@uploadFiles')->name('ugpc.bdgp.appsteel.uploadfiles');
    Route::post('/ugpc/bdgp/appfsteel/deletefiles', 'ApplicationSteelShaftController@deleteFiles')->name('ugpc.bdgp.appsteel.deletefiles');
    Route::post('/ugpc/bdgp/appfsteel/postapp', 'ApplicationSteelShaftController@postApp')->name('ugpc.bdgp.appsteel.postapp');
    //AppSteelShaft

    Route::get('/ugpc', 'UgpcController@index')->name('ugpc');
    
    //MachineController
    Route::get('/ugpc/workcard', 'MachineController@index')->name('ugpc.workcard');
    Route::post('/ugpc/getwarehouseshafts', 'MachineController@getWarehouseShafts')->name('ugpc.getwarehouseshafts');
    Route::post('/ugpc/getoperationsmachine', 'MachineController@getOperationsMachine')->name('ugpc.getoperationsmachine');
    Route::post('/ugpc/getsecondoperationsmachine', 'MachineController@getSecondOperationsMachine')->name('ugpc.getsecondoperationsmachine');
    Route::post('/ugpc/startoperation', 'MachineController@startOperation')->name('ugpc.startoperation');
    Route::post('/ugpc/getcurrentoperation', 'MachineController@getCurrentOperation')->name('ugpc.getcurrentoperation');
    Route::post('/ugpc/deleteoperation', 'MachineController@deleteOperation')->name('ugpc.deleteoperation');
    Route::post('/ugpc/closeoperation', 'MachineController@closeOperation')->name('ugpc.closeoperation');
    Route::post('/ugpc/consumpshaft', 'MachineController@consumpShaft')->name('ugpc.consumpshaft');
    Route::post('/ugpc/getconsumpshaft', 'MachineController@getConsumpShaft')->name('ugpc.getconsumpshaft');
    Route::post('/ugpc/deleteconsumpshaft', 'MachineController@deleteConsumpShaft')->name('ugpc.deleteconsumpshaft');
    Route::post('/ugpc/defectshaft', 'MachineController@defectShaft')->name('ugpc.defectshaft');
    Route::post('/ugpc/addprecopperplating', 'MachineController@addPreCopperPlating')->name('ugpc.addprecopperplating');
    //MachineController

    //InputConrol
    Route::get('/ugpc/inputcontrol/index', 'ShowInputControl');
    Route::post('/ugpc/inputcontrol/acceptedorder', 'ShowInputControl@acceptedOrder')->name('ugpc.inputcontrol.acceptedorder');
    //InputConrol

     //EngravingOrders

     Route::get('/ugpc/engravingorders', 'EngravingOrderController@index')->name('ugpc.engravingorders');
     Route::get('/ugpc/engravingorders/order/{id}', 'ShowOrderCard');
     Route::post('/ugpc/engravingorders/updateorder', 'EngravingOrderController@updateOrder')->name('ugpc.engravingorders.updateorder');
     Route::post('/ugpc/engravingorders/updateengravingtime', 'EngravingOrderController@updateEngravingTime')->name('ugpc.engravingorders.updateengravingtime');
     Route::post('/ugpc/engravingorders/alterationstage', 'EngravingOrderController@alterationStage')->name('ugpc.alterationstage');
     Route::post('/ugpc/engravingorders/skipdefect', 'EngravingOrderController@skipDefect')->name('ugpc.skipdefect');
     Route::post('/ugpc/engravingorders/replaceshaft', 'EngravingOrderController@replaceShaft')->name('ugpc.replaceshaft');
     
    //EngravingOrders

    //DailyPlan
    Route::get('/ugpc/dailyplan/index', 'ShowDailyPlan');
    Route::post('/ugpc/dailyplan/getdata', 'ShowDailyPlan@getData')->name('ugpc.dailyplan.getdata');
    Route::post('/ugpc/dailyplan/addcomment', 'ShowDailyPlan@addComment')->name('ugpc.dailyplan.addcomment');;
    //DailyPlan

    //ElectroPlating
    Route::get('/ugpc/electroplating/index', 'ShowElectroPlating');
    Route::post('/ugpc/electroplating/postsecondaryoperation', 'ShowElectroPlating@postSecondaryOperation')->name('ugpc.electroplating.postsecondaryoperation');
    //ElectroPlating

    //ReEngravingApp
    Route::get('/ugpc/reengravingapps', 'ReEngravingAppController@index')->name('ugpc.reengravingapps');
    Route::get('/ugpc/reengravingapps/createapp', 'ReEngravingAppController@createApp')->name('ugpc.reengravingapps.createapp');
    Route::post('/ugpc/reengravingapps/approvestage', 'ReEngravingAppController@approveStage')->name('ugpc.reengravingapps.approvestage');
    Route::post('/ugpc/reengravingapps/postapp', 'ReEngravingAppController@postApp')->name('ugpc.reengravingapps.postapp');
    Route::post('/ugpc/reengravingapps/createapp/search', 'ReEngravingAppController@searchShafts')->name('ugpc.reengravingapps.createapp.search');
    Route::post('/ugpc/reengravingapps/changeorder', 'ReEngravingAppController@changeOrder')->name('ugpc.reengravingapps.changeorder');
    Route::post('/ugpc/reengravingapps/translationstage', 'ReEngravingAppController@translationStage')->name('ugpc.reengravingapps.translationstage');
    Route::get('/ugpc/reengravingapps/app/{id}', 'ShowReEngravingApp');
    //ReEngravingApp

    //Statistic
    Route::get('/ugpc/statistic/', 'ProductionStatisticController@index')->name('ugpc.statistic.index');
    Route::get('/ugpc/statistic/get/data', 'ProductionStatisticController@getData')->name('ugpc.statistic.getData');
    Route::get('/ugpc/statistic/get/workcenters', 'ProductionStatisticController@getWorkCenters')->name('ugpc.statistic.getWorkCenters');
    Route::get('/ugpc/statistic/get/operations', 'ProductionStatisticController@getOperations')->name('ugpc.statistic.getOperations');
    Route::get('/ugpc/statistic/get/operatordata', 'ProductionStatisticController@getOperatorData')->name('ugpc.statistic.getOperatorData');
    Route::get('/ugpc/statistic/get/shiftdata', 'ProductionStatisticController@getShiftData')->name('ugpc.statistic.getShiftData');
    //Statistic

    //ProductionManager
    Route::get('/ugpc/productionmanager/', 'ProductionManagerController@index')->name('ugpc.productionmanager.index');
    Route::get('/ugpc/productionmanager/get/plan', 'ProductionManagerController@getPlan')->name('ugpc.productionmanager.getPlan');
    Route::post('/ugpc/productionmanager/save/plan', 'ProductionManagerController@savePlan')->name('ugpc.productionmanager.savePlan');
    //ProductionManager
});

//Route::namespace('UGPC')->prefix('ugpc')->middleware(['auth'])->group(function () {
//Route::get('/', 'HomeController@index')->name('ugpc');
//  });
