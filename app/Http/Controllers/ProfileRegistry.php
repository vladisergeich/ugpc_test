<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BDGP\Profile;
use App\Models\BDGP\Vendor;
use App\Models\BDGP\FingerprintParameter;
use App\Models\BDGP\PintingProcessParameter;
use App\Services\SOAP\SoapClient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class ProfileRegistry extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $profiles = Profile::all();
        $engravers = Vendor::all();
        $user = Auth::user();
     
        return view('ugpc.profileregistry.profilesregistry',[
            'engravers' => $engravers,
            'profiles' => $profiles,
            'user' => $user,
        ]);
    }

    public function getProfiles(Request $request)
    {
        $profiles = Profile::where('engraver',$request->vendor['vendor_name'])->where('type',$request->type)->get();

        return $profiles;
    }

    public function profileCard($id)
    {
        return view('ugpc.profilecard', [
                'profile' => Profile::with([
                'fingerPrintParam',
                'printingProcessParam',
            ])->findOrFail($id)
        ]);
    }

    public function addRow(Request $request)
    {
        switch ($request->type) {
            case 'finger_print':
                $row_count = FingerprintParameter::where('profile_id',$request->profile['id'])->count();
                $newRow = FingerprintParameter::updateOrCreate(
                    [
                        'profile_id' => $request->profile['id'],
                        'string_number' => $row_count+1,
                    ],
                );
                break;
            case 'printing_process':
                $row_count = PintingProcessParameter::where('profile_id',$request->profile['id'])->count();
                $newRow = PintingProcessParameter::updateOrCreate(
                    [
                        'profile_id' => $request->profile['id'],
                        'string_number' => $row_count+1,
                    ],
                );
                break;
        }

        $profile = Profile::with([
            'fingerPrintParam',
            'printingProcessParam',
        ])->findOrFail($request->profile['id']);

        return $profile;
    }

    public function deleteRow(Request $request)
    {
        switch ($request->type) {
            case 'finger_print':
                $row_count = FingerprintParameter::where('profile_id',$request->profile['id'])->orderBy('string_number','desc')->first()->delete();

                break;
            case 'printing_process':
                $row_count = PintingProcessParameter::where('profile_id',$request->profile['id'])->orderBy('string_number','desc')->first()->delete();
                break;
        }

        $profile = Profile::with([
            'fingerPrintParam',
            'printingProcessParam',
        ])->findOrFail($request->profile['id']);

        return $profile;
    }

    public function copyProfile(Request $request)
    {
        $copyProfile = Profile::with([
            'fingerPrintParam',
            'printingProcessParam',
        ])->where('short_name',$request->colyProfile)->first();

        if ($copyProfile->fingerPrintParam) {
            foreach ($copyProfile->fingerPrintParam as $param) {
                $newProfileParam = $param->replicate();
                $newProfileParam->profile_id = $request->profile['id'];
                $newProfileParam->save();
            }
        }

        if ($copyProfile->printingProcessParam) {
            foreach ($copyProfile->printingProcessParam as $param) {
                $newProfileParam = $param->replicate();
                $newProfileParam->profile_id = $request->profile['id'];
                $newProfileParam->save();
            }
        }

        $profile = Profile::with([
            'fingerPrintParam',
            'printingProcessParam',
        ])->findOrFail($request->profile['id']);

        return $profile;
    }

    public function addNewProfile(Request $request)
    {

        $newProfile = Profile::updateOrCreate(
            [
                'short_name' => $request->profile['short_name'],
            ],
            [   
                'supplier_analog_icc' => $request->profile['supplier_analog_icc'],
                'print' => $request->profile['print'],
                'engraver' => $request->profile['engraver'],
                'primary' => $request->profile['primary'],
                'secondary_housing' => $request->profile['secondary_housing'],
                'paint_series' => $request->profile['paint_series'],
                'type' => $request->type,
            ],
        );

        $profiles = Profile::where('engraver',$request->profile['engraver'])->where('type',$request->type)->get();

        return $profiles;
    }

    public function saveProfile(Request $request)
    {
        $profile = Profile::find($request->profile['id']);
        $profile->update($request->profile);

        foreach ($request->profile['finger_print_param'] as $param) {
            $finger_param = FingerprintParameter::find($param['id']);
            $finger_param->update($param);
        }

        foreach ($request->profile['printing_process_param'] as $param) {
            $print_param = PintingProcessParameter::find($param['id']);
            $print_param->update($param);
        }

        return $profile;
    }

    public function sendDataToNav(Request $request)
    {
        $profile = Profile::find($request->profile['id']);

        $array_passport=[];

        $printingParams = PintingProcessParameter::where('profile_id',$profile->id)->get();

        foreach ($printingParams as $key => $param) {

            $array_passport[$key]['profile_name']=$profile->short_name;
            $array_passport[$key]['string_number']=$param->string_number;
            $array_passport[$key]['color']=$param->color;
            $array_passport[$key]['paint_viscosity']=$param->paint_viscosity;
            $array_passport[$key]['solvent']=$param->solvent;
            $array_passport[$key]['inhibitor']=$param->inhibitor;
            $array_passport[$key]['percentage_inhibitor']=$param->percentage_inhibitor;
            $array_passport[$key]['coordinates_lab']=$param->coordinates_lab;
            $array_passport[$key]['eltex_value']=$param->eltex_value;
            $array_passport[$key]['raquel']=$param->raquel;
            $array_passport[$key]['optical_density']=$param->optical_density;
            $array_passport[$key]['raster_tone_50']=$param->raster_tone_50;
            $array_passport[$key]['raster_tone_15']=$param->raster_tone_15;
            $array_passport[$key]['raster_tone_5']=$param->raster_tone_5;
            
        }

        $data=new \stdClass;
        $data->PassportProfilePage_List=$array_passport;

        $options = [
            'login' => 'nav-port',
            'password' => 'Shambala12!'
        ];
        
        $url = 'http://10.10.40.78:7047/MicrosoftDynamicsNavServer/WS/%D0%9E%D0%9E%D0%9E%20%22%D0%94%D0%90%D0%9D%D0%90%D0%A4%D0%9B%D0%95%D0%9A%D0%A1-%D0%9D%D0%90%D0%9D%D0%9E%22/Page/PassportProfilePage';

        $client = new SoapClient($url,$options);
        $client->CreateMultiple($data);

        return 'ok'; 
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
