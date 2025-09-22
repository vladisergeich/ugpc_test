<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ugpc\ReEngravingApplication;
use App\Models\Ugpc\ReEngravingApplicationApprovStage;
use App\Models\Ugpc\ReEngravingApplicationShaft;
use App\Models\BDGP\Order;
use App\Models\BDGP\Shaft;
use App\Models\Ugpc\ApprovStage;
use App\Models\Ugpc\ReEngravingAppFile;
use App\Models\Ugpc\ApprovHistory;
use App\Models\BDGP\MacroOrder;
use App\Models\BDGP\ShaftOrder;
use App\Services\SOAP\SoapClient;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ReEngravingAppController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = ReEngravingApplication::pluck('customer_desc')->unique()->toArray();
        $orders = ReEngravingApplication::pluck('order_number')->unique()->toArray();
        $applications = ReEngravingApplication::with('shafts','reEngravingStage.approvStage','activeStage.approvStage','reEngravingStage.stageHistory.stage.approvStage','reEngravingStage.stageHistory.user','reEngravingStage.lastHistory.user')->get();

        return view('ugpc.reEngravingApp.application_list',[
            'applications' => $applications, 
            'customers' => $customers,
            'orders' => $orders,
        ]);
    }

    public function createApp()
    {
        return view('ugpc.reEngravingApp.create_app');
    }

    public function postApp(Request $request)
    {
        $user = Auth::user();

        $macroOrder = json_decode($request->macroOrder);
        
        $prodOrder = $this->getOrder($macroOrder->last_orders->prod_order);
        $place = $prodOrder['place'];
        $prodOrder = $prodOrder['order'];
    
        $newApp = ReEngravingApplication::updateOrCreate(
            [
                'macro_order_id' => $macroOrder->macro_id,
                'order_number' => $macroOrder->last_orders->prod_order,
            ],
            [
                'order_desc' => $prodOrder->Description,
                'novizna' => str_replace('_', ' ', $prodOrder->novizna) ?? ' ',
                'place' => $place,
                'customer_number' => $prodOrder->Klient,
                'customer_desc' => $prodOrder->CustomerName ?? null,
                'status' => 'Идет согласование',
                'start_date' => now()->format('Y-m-d'),
                'print_date' => $prodOrder->Printing_Date_Last ?? null,
                'planned_footage' => $prodOrder->PlannedFootage ?? null,
                'actual_footage' => $prodOrder->FactFootage ?? null,
                'machine_center' => $prodOrder->MachineCenter ?? null,
            ]
        );
    
        foreach ($request->shafts as $index => $shaft) {
            $shaftApp = ReEngravingApplicationShaft::updateOrCreate(
                [
                    'app_id' => $newApp->id,
                    'shaft_id' => $shaft['shaft_id'],
                ],
                [
                    'reason' => $shaft['reason'],
                    'comment' => $shaft['comment'],
                ]
            );
    
            if ($request->hasFile("shafts.{$index}.files")) {
                foreach ($request->file("shafts.{$index}.files") as $file) {
                    $filename = $file->getClientOriginalName(); // Получаем оригинальное имя файла
                    $path = $file->storeAs('uploads/reEngravingApps', $filename, 'public'); // Сохраняем файл
    
                    $fileModel = new ReEngravingAppFile([
                        'filename' => $filename,
                        'path' => $path,
                    ]);
                    $fileModel->app_shaft_id = $shaftApp->id;
                    $fileModel->save();
                }
            }
        }
    
        if (ReEngravingApplicationShaft::where('app_id', $newApp->id)->whereIn('reason', ['Изменение макета', 'Изменение параметров гравировки'])->count() > 0) {
            $stages = ApprovStage::all();
        } else {
            $stages = ApprovStage::where('title', '<>', 'ОКВИД')->get();
        }
    
        $stageNumber = 1;
        foreach ($stages as $stage) {
            $appStage = ReEngravingApplicationApprovStage::updateOrCreate(
                [
                    'app_id' => $newApp->id,
                    'stage_id' => $stage->id
                ],
                [
                    'stage_number' => $stageNumber,
                    'status' => match ($stageNumber) {
                        1 => 'Согласовано',
                        2 => 'Идет согласование',
                        default => null,
                    },
                ]
            );
    
            if ($stageNumber == 1) {
                ApprovHistory::updateOrCreate(
                [
                    'app_id' => $newApp->id,
                    'stage_id' => $appStage->id,
                ],
                [
                    'approved_date' => now()->format('Y-m-d'),
                    'status' => 'Заявка создана',
                    'comment' => '',
                    'user_id' => $user->id,
                ]);
            }
    
            $stageNumber += 1;
        }

        $emails = [
            'v.arsentev@danaflex.ru', 
            'a.maksytov@danaflex.ru', 
            'a.rezchikov@danaflex.ru', 
            'r.karimov@danaflex.ru',   
            'm.tinchurina@danaflex.ru',
            'l.sadikova@danaflex.ru',
            's.alekseeva@danaflex.ru',                                                  
        ];


        $data = array('link' => 'https://okvid.danaflex.ru/ugpc/reengravingapps/app/'.$newApp->id, 'app' => $newApp);
        foreach ($emails as $email) {
            Mail::send('ugpc/reEngravingApp/approv_mail',$data,function($message) use ($email){
                $message->to($email)->subject("Новая заявка на согласование гравировки цилиндров"." ".$place." "."ID"." ".$newApp->id." ".$prodOrder." ".$prodOrder->Klient);
                $message->from('d.portal@danaflex.ru','UGPC-Portal');
            });
        }


        $applications = ReEngravingApplication::with('reEngravingStage.approvStage', 'reEngravingStage.stageHistory')->get();
        $customers = ReEngravingApplication::where('status', 'Идет согласование')->pluck('customer_desc')->toArray();
        $orders = ReEngravingApplication::where('status', 'Идет согласование')->pluck('order_number')->toArray();
    
        return view('ugpc.reEngravingApp.application_list', [
            'applications' => $applications,
            'customers' => $customers,
            'orders' => $orders,
        ]);
    }
    

    public function getOrder($order) 
    {
        if ((mb_substr($order,0,1) == 'A') || (mb_substr($order,0,1) == 'a')){
            $place = 'Данафлекс-Алабуга';
        } else if ((mb_substr($order,0,1) == 'N') || (mb_substr($order,0,1) == 'n')) {
            $place = 'Данафлекс-НАНО';
        } else {
            $place = 'navZAO';
        }
        
        
        $options = [
            'login' => 'nav-port',
            'password' => 'Shambala12!'
        ];
        

        switch ($place) {
            case 'navZAO':
                $url = 'http://sqlserver.danaflex.local:7047/MicrosoftDynamicsNavServer/WS/%D0%97%D0%90%D0%9E%20%22%D0%94%D0%B0%D0%BD%D0%B0%D1%84%D0%BB%D0%B5%D0%BA%D1%81%22/Page/ProdOrderCard';
                break;
            case 'Данафлекс-НАНО':
                $url = 'http://dnsql.danaflex.local:7047/MicrosoftDynamicsNavServer/WS/%D0%9E%D0%9E%D0%9E%20%22%D0%94%D0%90%D0%9D%D0%90%D0%A4%D0%9B%D0%95%D0%9A%D0%A1-%D0%9D%D0%90%D0%9D%D0%9E%22/Page/ProdOrderCard';
                break;
            case 'Данафлекс-Алабуга':
                $url = 'http://sql-alabuga.danaflex.local:7047/MicrosoftDynamicsNavServer/WS/%D0%9E%D0%9E%D0%9E%20%22%D0%94%D0%90%D0%9D%D0%90%D0%A4%D0%9B%D0%95%D0%9A%D0%A1-%D0%90%D0%9B%D0%90%D0%91%D0%A3%D0%93%D0%90%22/Page/ProdOrderCard';
                break;
            default:
                return 'error place code';
                break;
        }
        
        $client = new SoapClient($url,$options);
                    
        $result = $client->ReadMultiple(
            array( 
                'filter'=>[ 
                    [
                    'Field' => 'No',
                    'Criteria' => "'{$order}'",
                    ],
                ],
                'setSize' => 1
            ) 
        );
        
        if (!empty($result->{'ReadMultiple_Result'})) {
            $result = $result->{'ReadMultiple_Result'};

            if (!empty($result->{'ProdOrderCard'})) {
                $prodOrder = $result->{'ProdOrderCard'};
            }
        }

        return ['order' => $prodOrder, 'place' => $place];
    }

    public function approveStage(Request $request)
    {
        $user = Auth::user();

        $app = ReEngravingApplication::find($request->app['id']);
        $app->update($request->app);
        if ($activeStage = ReEngravingApplicationApprovStage::find($request->app['active_stage']['id'])) {
            $activeStage->update(['status' => $request->status]);

            ApprovHistory::create(
                [
                    'app_id' => $request->app['id'],
                    'stage_id' => $request->app['active_stage']['id'],
                    'approved_date' => now()->format('Y-m-d'),
                    'status' => $request->status,
                    'comment' => $request->app['active_stage']['comment'] ?? null,
                    'user_id' => $user->id,
                ]);
        }

        switch ($request->status) {
            case 'Согласовано':
                if ($nextStage = ReEngravingApplicationApprovStage::with('approvStage')->where('app_id',$request->app['id'])->where('stage_number','>',$request->app['active_stage']['stage_number'])->orderBy('stage_number')->first()) {
                    $nextStage->update(['status' => 'Идет согласование']);
                }

                switch ($nextStage->approvStage->title) {
                    case 'ОГТ/ОДП':
                        switch ($app->place) {
                            case 'navZAO':
                                $emails = [   
                                    's.artemev@danaflex.ru',
                                    'v.kazakov@danaflex.ru',                                                 
                                    'v.arsentev@danaflex.ru', 
                                ];
                                break;
                            case 'navNANO':
                                $emails = [  
                                    'a.maksytov@danaflex.ru', 
                                    'a.rezchikov@danaflex.ru', 
                                    'r.karimov@danaflex.ru', 
                                    'e.gabitova@danaflex.ru', 
                                    'r.galiulin@danaflex.ru',                              
                                    'v.arsentev@danaflex.ru', 
                                ];
                                break;
                            case 'navALABUGA':
                                $emails = [  
                                    'i.gaifetdinov@danaflex.ru', 
                                    'd.halimova@danaflex.ru', 
                                    'a.rahmeev@danaflex.ru', 
                                    'l.sharipov@danaflex.ru',                                                      
                                    'v.arsentev@danaflex.ru', 
                                ];
                                break;
                            default:
                                break;
                        }
                        break;
                    case 'ОП/ОСЗ':
                        $emails = [                                                       
                            'osz@danaflex.ru', 
                            'v.arsentev@danaflex.ru', 
                            'salesgy@danaflex.ru', 
                        ];
                        break;
                    case 'ОПЛ':
                        $emails = [                                                       
                            'm.tinchurina@danaflex.ru',
                            'l.sadikova@danaflex.ru',
                            's.alekseeva@danaflex.ru',
                        ];
                        break;
                    case 'ОКВИД':
                        $emails = [                                                       
                            'ionich@danaflex.ru',
                            'o.podgorodov@danaflex.ru',
                            'n.marchenkov@danaflex.ru',
                            'ar.cvetkov@danaflex.ru',
                            'd.kalinin@danaflex.ru',
                            'a.smolik@danaflex.ru',
                            'baranov@danaflex.ru',
                            'l.sharipov@danaflex.ru',
                        ];
                        break;
                    case 'Координатор':
                        $emails = [
                            'v.arsentev@danaflex.ru', 
                            'm.tinchurina@danaflex.ru',
                            'l.sadikova@danaflex.ru',
                            's.alekseeva@danaflex.ru',
                        ];
                        break;
                    default:
                        return 'error stage code';
                        break;
                }

                $data = array('link' => 'https://okvid.danaflex.ru/ugpc/reengravingapps/app/'.$app->id, 'app' => $app);
                foreach ($emails as $email) {
                    Mail::send('ugpc/reEngravingApp/approv_mail',$data,function($message) use ($email){
                        $message->to($email)->subject("Согласование гравировки цилиндров"." ".$place." "."ID"." ".$newApp->id." ".$prodOrder." ".$prodOrder->Klient);
                        $message->from('d.portal@danaflex.ru','UGPC-Portal');
                    });
                }

                break;
            case 'Не гравировать':
                $app->update(['status' => 'Гравировка не согласована']);

                $activeStages = ReEngravingApplicationApprovStage::where('app_id',$request->app['id'])->where('stage_id','>',1)->orderBy('stage_number')->update(['status' => null]);

                if ($nextStage = ReEngravingApplicationApprovStage::where('app_id',$request->app['id'])->where('stage_id',1)->first()) {
                    $nextStage->update(['status' => 'Идет согласование']);
                }

                $reEngravingShafts = ReEngravingApplicationShaft::where('app_id', $request->app['id'])->get();

                foreach ($reEngravingShafts as $reEngravingShaft) {
                    $shaft = Shaft::where('shaft_id',$reEngravingShaft->shaft_id)->first();

                    $shaft->condition = 'Гравировка не согласована';
                    $shaft->save();
                }

                break;
            case 'Нет заявки':
                $app->update(['status' => 'Ожидание заказа']);

                $activeStages = ReEngravingApplicationApprovStage::where('app_id',$request->app['id'])->where('stage_id','>',1)->orderBy('stage_number')->update(['status' => null]);

                if ($nextStage = ReEngravingApplicationApprovStage::where('app_id',$request->app['id'])->where('stage_id',1)->first()) {
                    $nextStage->update(['status' => 'Идет согласование']);
                }

                $reEngravingShafts = ReEngravingApplicationShaft::where('app_id', $request->app['id'])->get();

                foreach ($reEngravingShafts as $reEngravingShaft) {
                    $shaft = Shaft::where('shaft_id',$reEngravingShaft->shaft_id)->first();

                    $shaft->condition = 'Ожидание заказа';
                    $shaft->save();
                }
                break;
            case 'Выполнено':
                $app->update(['status' => 'Выполнено']);
                break;
            default:
                return 'error place code';
                break;
        }

        return $app;

    }

    public function updateApp(Request $request)
    {
        if ($app = ReEngravingApplication::where('order_number',$request['PO'])->first()) {
            $app->actual_footage = $request['data']['Volume_m'];
            $app->print_date = $request['data']['Starting_Date_Fakt'];
            $app->save();
        }

        return $app;
    }

    public function changeOrder(Request $request)
    {
        $user = Auth::user();

        $app = ReEngravingApplication::find($request->app['id']);

        $prodOrder = $this->getOrder($request->order);
        $place = $prodOrder['place'];
        $prodOrder = $prodOrder['order'];

        if ($prodOrder) {
            ApprovHistory::create(
                [
                    'app_id' => $request->app['id'],
                    'stage_id' => $request->app['active_stage']['id'],
                    'approved_date' => now()->format('Y-m-d'),
                    'status' => 'Изменение номера заказа',
                    'comment' => $app->order_number .'=>'.$request->order ?? null,
                    'user_id' => $user->id,
                ]);

            $app->update(
                [
                'order_number' => $request->order,
                'order_desc' => $prodOrder->Description,
                'novizna' => str_replace('_', ' ', $prodOrder->novizna) ?? ' ',
                'place' => $place,
                'customer_number' => $prodOrder->Klient,
                'customer_desc' => $prodOrder->CustomerName,
                'status' => 'Идет согласование',
                'start_date' => now()->format('Y-m-d'),
                'print_date' => $prodOrder->Printing_Date_Last ?? null,
                'planned_footage' => $prodOrder->PlannedFootage ?? null,
                'actual_footage' => $prodOrder->FactFootage ?? null,
                'machine_center' => $prodOrder->MachineCenter ?? null,
                ]
            );
        }

        $app = ReEngravingApplication::with('shafts.shaftOrder.order','activeStage.approvStage','shafts.lastOkvid.order','shafts.resourse')->findOrFail($request->app['id']);

        return $app;
    }

    public function searchShafts(Request $request)
    {
        if ($request->type == 'okvid') {
            $shafts = MacroOrder::where('macro_id',$request->searchText)->with('lastOrders','orders.shafts')->first();
        }
        
        return $shafts;
    }

    public function translationStage(Request $request)
    {
        $user = Auth::user();

        $activeStages = ReEngravingApplicationApprovStage::where('app_id',$request->app['id'])->where('id','>',$request->stage)->orderBy('stage_number')->update(['status' => null]);

        if ($nextStage = ReEngravingApplicationApprovStage::find($request->stage)) {
            $nextStage->update(['status' => 'Идет согласование']);
        }
        
        return $nextStage;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
