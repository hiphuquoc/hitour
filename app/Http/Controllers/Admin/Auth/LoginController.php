<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    // use AuthenticatesUsers;

    public function showLoginForm(): View {
        // User::create([
        //     'name'      => 'thachnv',
        //     'email'     => 'nvthach92@gmail.com',
        //     'password'  => Hash::make('hitourVN@mk123')
        // ]);
        return view('admin.auth.login');
    }

    public function login(Request $request){
        if(Auth::attempt($request->only('email', 'password'))){
            return redirect()->route('admin.tourLocation.list');
        }
        session()->flash('error', 'Email và Password đăng nhập không hợp lệ!');
        return back();
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('admin.showLoginForm');
    }
}
