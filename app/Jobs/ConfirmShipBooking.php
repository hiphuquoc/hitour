<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Mail;
use App\Mail\SendMail;

class ConfirmShipBooking implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $idShipBooking;
    
    public function __construct($idShipBooking){
        $this->idShipBooking = $idShipBooking;
    }
    
    public function handle(){
        $infoShipBooking        = \App\Models\ShipBooking::select('*')
                                    ->where('id', $this->idShipBooking)
                                    ->with('customer_contact', 'infoDeparture', 'status', 'customer_list')
                                    ->first();
        $addressMail            = $infoShipBooking->customer_contact->email ?? null;
        /* nếu có email mới gửi mail */
        if(!empty($addressMail)){
            $typeDeparture      = $infoShipBooking->infoDeparture->count()==2 ? ' (khứ hồi)' : null;
            $titleMail          = 'HItour.VN - Xác nhận đặt vé '.$infoShipBooking->infoDeparture[0]->departure.' - '.$infoShipBooking->infoDeparture[0]->location.$typeDeparture.' số '.$infoShipBooking->no.' - '.date('H:i d/m/Y', time());
            $bodyMail           = view('admin.shipBooking.confirmBooking', ['item' => $infoShipBooking]);
            $dataMail           = [
                'title' => $titleMail,
                'body'  => $bodyMail
            ];
            Mail::to($addressMail)->send(new SendMail($dataMail));
        }
    }
}
