<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\Controller;
use Illuminate\Http\Request;
use App\Models\HoroscopeClient;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    
    /**
     * Method is used to authenticate user and create token
     */
    public function auth_api_user(Request $request) {

        // $user_creds = $request->only(['email_id', 'password']);
        // $email = ($request->has('email')) ? trim($request->get('email')) : '';
        // $api_key = ($request->has('api_key')) ? trim($request->get('api_key')) : '';
        $user_id = ($request->has('id')) ? trim($request->get('id')) : '';
        // $password = ($request->has('password')) ? trim($request->get('password')) : '';

        // $user = HoroscopeClient::select('*')
        //                     ->where('email_id', $email)
        //                     ->where('api_key', $api_key)
        //                     ->first();
                            
        $user = HoroscopeClient::select('*')
                            ->where('id', $user_id)
                            ->first();

        // $token = auth()->attempt(['email_id' => $email, 'password' => $password]);
        
        // if (!$token = auth()->attempt($user_creds)) {
        // if (!$token = auth()->attempt(['email_id' => $email_id, 'id' => 515])) {
        if (!isset($user->id) || $user->id == null || $user->id <= 0 || !$token = Auth::login($user)) {
            return response()->json(['success' => 2, 'msg' => 'Incorrect User id']);
        }
        // $token = auth()->setTTL(60)->attempt($credentials);
        // $payload = 'test'; //auth()->payload();
        // dd($token, $payload);
        // return $token;
        HoroscopeClient::where('id', $user->id)->update(['api_acc_token' => $token]);
        return response()->json(['success' => 1, 'msg' => 'Token generated successfully!']);

    }

    /**
     * Method is used to block old token and generate new token of user
     */
    public function refresh_token(Request $request) {

        // $email = ($request->has('email')) ? trim($request->get('email')) : '';
        // $api_key = ($request->has('api_key')) ? trim($request->get('api_key')) : '';
        $user_id = ($request->has('id')) ? trim($request->get('id')) : '';

        $user = HoroscopeClient::select('*')
                            ->where('id', $user_id)
                            ->first();

        if (!isset($user->id) || $user->id == null || $user->id <= 0 || !$token = Auth::refresh(true, true)) {
            return response()->json(['success' => 2, 'msg' => 'Incorrect user id']);
        }

        HoroscopeClient::where('id', $user->id)->update(['api_acc_token' => $token]);
        return response()->json(['success' => 1, 'msg' => 'Token re-generated successfully!']);

    }

}
