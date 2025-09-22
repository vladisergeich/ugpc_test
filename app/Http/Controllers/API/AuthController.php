<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Danaflex\KeycloakWebGuard\Facades\KeycloakWeb;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    
    public function login(Request $request)
    {
        $token = KeycloakWeb::getAccessTokenByPassword($request->username, $request->password);
        KeycloakWeb::saveToken($token);
        Auth::validate($token);
        $user = Auth::user();

        return [$token,$user];
    }
}
