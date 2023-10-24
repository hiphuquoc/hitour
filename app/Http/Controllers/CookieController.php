<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;

class CookieController extends Controller {
    public static function setCookie($cookieName, $cookieValue, $minutes = 60){
        $response = Cookie::queue($cookieName, $cookieValue, $minutes);
        return $response;
    }

    public static function getCookie($cookieName) {
        $response = Cookie::get($cookieName);
        return json_decode($response, true);
    }

    public static function deleteCookie($cookieName){
        $response = new Response('Cookie has been deleted');
        $response->withCookie(Cookie::forget($cookieName));
        return $response;
    }
}