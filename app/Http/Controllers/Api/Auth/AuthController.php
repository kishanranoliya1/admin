<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HoroscopeClient;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public const OPENSSL_CHIPER_ALGORITHM          = 'AES-128-CTR';
    public const D_ENCRYPT_KEY       = 'Iklso254opfdgEO9soidETgof4po5';
    
    /**
     * Method is used to authenticate user and create token
     */
    public function auth_api_user(Request $request) {
        $text = $request->id;
        // Non-NULL Initialization Vector for decryption
        $decryption_iv = '1234567891011121';
        $options = 0;
        // Store the decryption key
        $decryption_key = self::D_ENCRYPT_KEY;
        // Use openssl_decrypt() function to decrypt the data
        $user_id =  openssl_decrypt ($text, self::OPENSSL_CHIPER_ALGORITHM, $decryption_key, $options, $decryption_iv);
                            
        $user = HoroscopeClient::select('*')
                            ->where('id', $user_id)
                            ->first();

        if (!isset($user->id) || $user->id == null || $user->id <= 0) {
            return response()->json(['success' => 2, 'msg' => 'Incorrect User id']);
        } else{
            HoroscopeClient::where('id', $user->id)->update(['api_acc_token' => $user->token]);
            return response()->json(['success' => 1, 'msg' => 'Token generated successfully!']);
        }

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
