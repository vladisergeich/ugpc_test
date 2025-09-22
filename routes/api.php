<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Portal\User\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/bdgp/printed/','BdgpController@okvidPrinted', ['middleware' => 'auth.basic', function() {
    // Только аутентифицированные пользователи могут войти
  }]);

Route::get('/bdgp/okvidcondition/','BdgpController@okvidCondition', ['middleware' => 'auth.basic', function() {
  // Только аутентифицированные пользователи могут войти
}]);

Route::get('/bdgp/importokvid/','BdgpController@importOkvidComplect', ['middleware' => 'auth.basic', function() {
  // Только аутентифицированные пользователи могут войти
}]);

Route::get('/bdgp/importokvidplate/','BdgpController@importOkvidPlate', ['middleware' => 'auth.basic', function() {
  // Только аутентифицированные пользователи могут войти
}]);

Route::get('/bdgp/importokvidstream/','BdgpController@importOkvidStream', ['middleware' => 'auth.basic', function() {
  // Только аутентифицированные пользователи могут войти
}]);

Route::get('/bdgp/importgpinfo/','BdgpController@importGpInfo', ['middleware' => 'auth.basic', function() {
  // Только аутентифицированные пользователи могут войти
}]);

Route::get('/bdgp/importmacroinfo/','BdgpController@importMacroInfo', ['middleware' => 'auth.basic', function() {
  // Только аутентифицированные пользователи могут войти
}]);

/*
Route::post('/ugpc/inputshaftdata/','UgpcController@inputShaftData', ['middleware' => 'auth.basic', function() {
  // Только аутентифицированные пользователи могут войти
}]);

login
*/

Route::post('/login', 'API\AuthController@login');

Route::post('/ugpc/inputcontrol/infoshaft', 'InputControlController@infoShaft')->middleware('auth:api');
Route::post('/ugpc/inputcontrol/acceptshaft', 'InputControlController@acceptShaft')->middleware('auth:api');
Route::post('/ugpc/infooperator', 'UgpcController@infoOperator')->middleware('auth:api');

Route::post('/ugpc/electroplating/shaftinfo', 'ElectroPlating@shaftInfo')->middleware('auth:api');
Route::post('/ugpc/electroplating/getoperation', 'ElectroPlating@getOperation')->middleware('auth:api');
Route::post('/ugpc/electroplating/startoperationbath', 'ElectroPlating@startOperationBath')->middleware('auth:api');
Route::post('/ugpc/electroplating/closeoperationbath', 'ElectroPlating@closeOperationBath')->middleware('auth:api');
Route::post('/ugpc/electroplating/сonsumablesinfo', 'ElectroPlating@сonsumablesInfo')->middleware('auth:api');

Route::post('/ugpc/electroplating/brakshaft', 'ElectroPlating@brakShaft')->middleware('auth:api');


Route::get('/bdgp/importmacroinfo3/','BdgpController@importMacroInfo', ['middleware' => 'auth.basic',]);

Route::middleware(['auth.basic'])->get('/bdgp/importmacroinfo2/','BdgpController@importMacroInfo2');


Route::post('/ugpc/engravingorder/infoshaft', 'EngravingOrderController@infoShaft')->middleware('auth:api');
Route::post('/ugpc/engravingorder/acceptshaft', 'EngravingOrderController@acceptShaft')->middleware('auth:api');

Route::post('/ugpc/getopenoperations', 'MachineController@getOpenOperationsWorkCenter')->middleware('auth:api');
Route::post('/ugpc/startoperation', 'MachineController@startOperation')->middleware('auth:api');
Route::post('/ugpc/closeoperation', 'MachineController@closeOperation')->middleware('auth:api');
Route::post('/ugpc/defectshaft', 'MachineController@defectShaft')->middleware('auth:api');

Route::post('/ugpc/reengravingapps/updateapp', 'ReEngravingAppController@updateApp')->middleware('auth:api');

