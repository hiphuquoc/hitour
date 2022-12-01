<?php

namespace App\Http\Controllers;

use App\Jobs\ConfirmShipBooking;
use Illuminate\Http\Request;
use Mail;
use App\Mail\SendMail;

use App\Models\ShipBooking;

class MailController extends Controller {

    public function test(Request $request){
        $idShipBooking      = $request->get('id') ?? 28;
        /* tạo job */
        ConfirmShipBooking::dispatch($idShipBooking);
        dd('tạo Job thành công');
    }

}
