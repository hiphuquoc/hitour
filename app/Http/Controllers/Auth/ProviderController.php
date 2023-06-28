<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Google\Client as Google_Client;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Exception;

class ProviderController extends Controller {

    public function googleCallback(Request $request){
        if ($_COOKIE['g_csrf_token'] !== $request->input('g_csrf_token')) {
            // Invalid CSRF token
            return back();
        }
        $idToken = $request->input('credential');
        $client = new Google_Client([
            'client_id' => env('GOOGLE_CLIENT_ID')
        ]);
        $payload = $client->verifyIdToken($idToken);
        if (!$payload) return back();
        try {
            /* tìm thông tin user */
            $finduser           = User::select('*')
                                    ->where('email', $payload['email'])
                                    ->first();
            /* cập nhật thêm dữ liệu cho tài khoản nếu thiếu */
            if(!empty($finduser)){
                $dataUpdate     = [
                    'provider'      => 'google',
                    'provider_id'   => $payload['sub']
                ];
                if(empty($finduser->password)){
                    $dataUpdate['password'] = Hash::make(config('main.password_user_default'));
                }
                User::updateItem($finduser->id, $dataUpdate);
                Auth::login($finduser);
            }else {
                $arrayCreate = [
                    'email'         => $payload['email'],
                    'name'          => $payload['name'],
                    'provider'      => 'google',
                    'provider_id'   => $payload['sub'],
                    'password'      => Hash::make(config('main.password_user_default'))
                ];
                $newUser = User::create($arrayCreate);
                Auth::login($newUser);    
            }
            return redirect($_SERVER['HTTP_REFERER']);
        } catch (Exception $e) {
            return redirect('/');
        }
    }

    public static function facebookRedirect(){
        return Socialite::driver('facebook')->redirect();
    }

    public static function facebookCallback(){
        dd(123);
        $user = Socialite::driver('facebook')->user();
         
        $finduser = User::where('facebook_id', $user->id)->first();
    }

}
