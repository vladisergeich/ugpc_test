<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Vendor, Profile, Material};
use Inertia\Inertia;
use App\Services\SOAP\SoapClient;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Profile/Index', [
            'vendors' => Vendor::all(),
            'materials' => Material::all(),
            'profiles' => session('profiles', []),
        ]);
    }

    public function getProfiles(Request $request)
    {
        return back()->with([
            'profiles' => Profile::with('vendor','primary','secondary')->where('engraver_id',$request->id)->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255',
            'print_type' => 'nullable|string',
            'supplier_analog_icc' => 'nullable|string',
            'engraver_id' => 'nullable|integer',
            'primary_material_id' => 'nullable|integer',
            'secondary_material_id' => 'nullable|integer',
            'paint_series' => 'nullable|string',
        ]);
    
        Profile::create($validated);
    
        return redirect()->back()->with('success', 'Профиль успешно создан');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Profile $profile)
    {
        return Inertia::render('Profile/Show', [
            'profile' => $profile->load('vendor','primary','secondary','fingerPrintColors','printProcessColors'),
            'vendors' => Vendor::all(),
            'materials' => Material::all(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function sendToNav(Request $request)
    {
        $profile = Profile::with('printProcessColors')->findOrFail($request->id);

        $array_passport=[];

        foreach ($profile->printProcessColors as $key => $param) {

            $array_passport[$key]['profile_name']=$profile->code;
            $array_passport[$key]['string_number']=$param->sequence;
            $array_passport[$key]['color']=$param->color;
            $array_passport[$key]['paint_viscosity']=$param->viscosity;
            $array_passport[$key]['solvent']=$param->solvent;
            $array_passport[$key]['inhibitor']=$param->inhibitor['value'];
            $array_passport[$key]['percentage_inhibitor']=$param->inhibitor['percentage'];
            $array_passport[$key]['coordinates_lab']=$param->coordinates_lab;
            $array_passport[$key]['eltex_value']=$param->eltex_value;
            $array_passport[$key]['raquel']=$param->raquel;
            $array_passport[$key]['optical_density']=$param->optical_density['value'];
            $array_passport[$key]['raster_tone_50']=$param->raster_tone_50['value'];
            $array_passport[$key]['raster_tone_15']=$param->raster_tone_15['value'];
            $array_passport[$key]['raster_tone_5']=$param->raster_tone_5['value'];
            $array_passport[$key]['raster_tone_50_admission']=$param->raster_tone_50['admission'];
            $array_passport[$key]['raster_tone_15_admission']=$param->raster_tone_15['admission'];
            $array_passport[$key]['raster_tone_5_admission']=$param->raster_tone_5['admission'];
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

        return back()->with([
            'profile' => $profile->load('vendor','primary','secondary','fingerPrintColors','printProcessColors'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $profile = Profile::findOrFail($request->id);
        $profile->update($request->all());
        
        return back()->with([
            'profile' => $profile->load('vendor','primary','secondary','fingerPrintColors','printProcessColors'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
