<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BDGP\Profile;
use App\Models\BDGP\Vendor;
use App\Models\BDGP\ProfilePassport;
use App\Services\SOAP\SoapClient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShowFlexoProfileRegistry extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $vendors = Vendor::where('type','flexo')->get();
        $user = Auth::user();
     
        return view('ugpc.profilesregistry',[
            'vendors' => $vendors,
            'user' => $user,
            'type' => 'flexo',
        ]);
    }
}
