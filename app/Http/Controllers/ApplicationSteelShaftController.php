<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BDGP\SteelShaftApplication;
use App\Models\BDGP\Shaft;
use App\Models\BDGP\SteelShaftApplicationComposition;
use App\Models\BDGP\FormatVal;
use App\Models\BDGP\Vendor;
use App\Models\BDGP\Customer;
use App\Models\BDGP\AppSteelFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Exports\AppSteelExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Mail\AppSteelExcelFileMail;
use App\Http\Controllers\HandBookController;
use App\Models\Ugpc\MovementOrder;

class ApplicationSteelShaftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $applications = SteelShaftApplication::with('customer','firstComposition')->get();
        $formats = FormatVal::orderBy('format', 'asc')->get();
        $vendors = Vendor::all()->pluck('vendor_name');
        $customers = Customer::all();

        return view('ugpc.applicationSteelShaft.application_list',[
            'applications' => $applications, 
            'formats' => $formats,
            'vendors' => $vendors,
            'customers' => $customers,
        ]);
    }

    public function submitApp(Request $request)
    {
        $app_steel = SteelShaftApplication::find($request['id']);
        $app_steel->update($request->all());
        
        return $app_steel;
    }

    public function addApplication(Request $request)
    {
        $last_application = SteelShaftApplication::latest()->first();

        $new_app = new SteelShaftApplication();
        if ($last_application != null) {
            $new_app->document_number = $last_application->document_number+1;
        }else {
            $new_app->document_number = 1;
        }
        $new_app->okvid_number = '';
        $new_app->vendor = '';
        $new_app->engraver = '';
        $new_app->format = '';
        $new_app->document_date = date('Y-m-d');
        $new_app->comment = 'Номер цилиндра набить с двух сторон со смещением 45°';
        $new_app->save();

        return $new_app;
    }

    public function uploadFiles(Request $request)
    {
    
        $file = $request->file;
        $filename = $file->getClientOriginalName();
        $path = $file->storeAs('uploads', $filename, 'public');
        $fileModel = new AppSteelFile([
            'filename' => $filename,
            'path' => $path,
        ]);

        $fileModel->document_number = $request->query('document_number');
        $fileModel->save();

        $files = AppSteelFile::where('document_number',$request->query('document_number'))->get();

        return $files;
    }

    public function deleteFiles(Request $request)
    {
        $filePath = 'uploads/' . $request->query('filename');

        Storage::delete($filePath);

        AppSteelFile::where('filename',$request->query('filename'))->where('document_number',$request->query('document_number'))->delete();

        $files = AppSteelFile::where('document_number',$request->query('document_number'))->get();

        return $files;
    }

    

    public function getShaftsList(Request $request)
    {
        $shafts_list = SteelShaftApplicationComposition::where('document_number',$request->document_number)->get();
        $files = AppSteelFile::where('document_number',$request->document_number)->get();

        return [$shafts_list,$files];
    }

    public function deleteAllShafts(Request $request)
    {
        $shafts_list = SteelShaftApplicationComposition::where('document_number',$request->document_number)->delete();
        $shafts_list = SteelShaftApplicationComposition::where('document_number',$request->document_number)->get();

        return $shafts_list;
    }

    public function deleteShaft(Request $request)
    {
        
        $shafts_list = SteelShaftApplicationComposition::where('id',$request->id)->delete();
        $shafts_list = SteelShaftApplicationComposition::where('document_number',$request->document_number)->get();

        $number = 1;
        foreach ($shafts_list as $shaft) {
            $shaft->shaft_number = $number;
            $shaft->save();

            $number = $number+1;
        }


        return $shafts_list;
    }

    

    public function postApp(Request $request)
    {
        $app_steel = SteelShaftApplication::where('document_number',$request->document_number)->first();
        $shafts_list = SteelShaftApplicationComposition::where('document_number',$request->document_number)->get();

        if ($request->vendor == 'Яношка-Павловск') {
            $emails = [
                'm.tinchurina@danaflex.ru',
                'l.sadikova@danaflex.ru ',
                'd.biktimirov@danaflex.ru',
                'ra.gilfanov@danaflex.ru', 
                'ivan.okhten@janoschka.com', 
                'sergey.anisimov@janoschka.com', 
                'irina.ursina@janoschka.com', 
                'janoschka.pavlovsk2018@gmail.com',
                'valentina.kiuria@janoschka.com',
                'anna.lyach@janoschkapavlovsk.com',
            ];
        } else {
            $emails = [
                'novyuncheng@mail.ru',
                'm.tinchurina@danaflex.ru',
                'l.sadikova@danaflex.ru',
                'd.biktimirov@danaflex.ru',              
                'ra.gilfanov@danaflex.ru',  
            ];
        }

        $files = AppSteelFile::where('document_number',$request->document_number)->get();

        $export = Excel::download(new AppSteelExport($request->document_number),'shafts.xlsx');

        $tempExcelFilePath = tempnam(sys_get_temp_dir(), 'temp_excel_');
        \Maatwebsite\Excel\Facades\Excel::store($export, $tempExcelFilePath, 'temp',\Maatwebsite\Excel\Excel::XLSX);

        $otherFilePaths = $files->pluck('path')->toArray();


        if ($shafts_list != null) {
        Mail::to($emails)
            ->send(new AppSteelExcelFileMail($export, $otherFilePaths, $request->document_number));
        }
        
        unlink($tempExcelFilePath);

        $app_steel->post = true;
        $app_steel->save();

        $handBookController = new HandBookController();
        $handBookController->newShaft($request->document_number);

        foreach ($shafts_list as $shaft_list) {
            $shaft = Shaft::where('shaft_id',$shaft_list->shaft_id)->first();
            $shaft->warehouse = $app_steel->vendor;
            $shaft->save();
        }

        $engravingOrderUpdate = MovementOrder::create(
            [   
                'order_number' => $app_steel->order_number,
                'shaft_quantity' => $app_steel->shaft_quantity,
                'engraver' => $app_steel->engraver,
                'description' => $app_steel->document_date.'  '.'Заявка на изготовление стальной заготовки'.'  №'.$app_steel->document_number.'  '.'ф.'.$app_steel->format.'  .'.$app_steel->vendor,
            ],
        );
        
        return $app_steel;
    }

    public function addShafts(Request $request)
    {
        $shafts_list = SteelShaftApplicationComposition::where('document_number',$request->app['document_number'])->latest()->first();

        if ($shafts_list != null) {
            $shaft_number = $shafts_list->shaft_number+1;
        } else {
            $shaft_number = 1;
        }
        $shafts_qty = $request->shafts_qty;
        $shaft_id = $request->start_shaft_id;
        while ($shafts_qty > 0) {
            $new_shaft = new SteelShaftApplicationComposition();
            $new_shaft->document_number = $request->app['document_number'];
            $new_shaft->shaft_id = $shaft_id;
            $new_shaft->shaft_number = $shaft_number;
            $new_shaft->ff = $request->shaft_ff;
            if ($request->shaft_ff == 'HS') {
                $new_shaft->diameter = $request->app['format']*1.0013/3.1416-0.4;
            }else {
                $new_shaft->diameter = $request->app['format']/3.1416-0.4;
            }
            if ($request->shaft_ff == 'HS') {
                $new_shaft->shaft_ra = '1,3-1,8';
                $new_shaft->shaft_rz = '5,5-7,2';
            }
            $new_shaft->save();

            $shaft_id = $shaft_id+1;
            $shaft_number = $shaft_number+1;
            $shafts_qty = $shafts_qty-1;
        }

        $shafts_list = SteelShaftApplicationComposition::where('document_number',$request->app['document_number'])->get();

        return $shafts_list;
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
