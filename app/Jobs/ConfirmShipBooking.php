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

    private $infoShipBooking;
    private $infoStaff;
    private $typeEmail;
    
    public function __construct($infoShipBooking, $infoStaff, $typeEmail='confirm'){
        $this->infoShipBooking      = $infoShipBooking;
        $this->infoStaff            = $infoStaff;
        $this->typeEmail            = $typeEmail;
    }
    
    public function handle(){
        $typeDeparture          = $this->infoShipBooking->infoDeparture->count()==2 ? ' (khứ hồi)' : null;
        if($this->typeEmail=='notice'){
            /* trường hợp gửi thông báo bằng email khi có booking mới */
            $addressMail        = config('company.email');
            $titleMail          = 'Thông báo booking '.$this->infoShipBooking->infoDeparture[0]->departure.' - '.$this->infoShipBooking->infoDeparture[0]->location.$typeDeparture.' số '.$this->infoShipBooking->no.' - '.date('H:i d/m/Y', time());
            $bodyMail           = view('admin.shipBooking.confirmBooking', [
                'item' => $this->infoShipBooking, 
                'infoStaff' => $this->infoStaff, 
                'type' => $this->typeEmail
            ]);
        }else {
            /* trường hợp gửi xác nhận cho khách */
            $addressMail        = $this->infoShipBooking->customer_contact->email;
            $titleMail          = config('company.webname').' - Xác nhận đặt vé '.$this->infoShipBooking->infoDeparture[0]->departure.' - '.$this->infoShipBooking->infoDeparture[0]->location.$typeDeparture.' số '.$this->infoShipBooking->no.' - '.date('H:i d/m/Y', time());
            $bodyMail           = view('admin.shipBooking.confirmBooking', [
                'item' => $this->infoShipBooking, 
                'infoStaff' => $this->infoStaff
            ]);
        }
        $dataMail           = [
            'title' => $titleMail,
            'body'  => $bodyMail
        ];
        Mail::to($addressMail)->send(new SendMail($dataMail));
    }
}
