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

class ConfirmBooking implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $infoBooking;
    private $infoStaff;
    private $typeEmail;
    
    public function __construct($infoBooking, $infoStaff, $typeEmail='confirm'){
        $this->infoBooking          = $infoBooking;
        $this->infoStaff            = $infoStaff;
        $this->typeEmail            = $typeEmail;
    }
    
    public function handle(){
        /* webname - Xác nhận đặt Tour số ... - 12:20 07/12/2022 */
        $typeBooking        = 'dịch vụ';
        if(!empty($this->infoBooking->service)) $typeBooking = 'Vé vui chơi';
        if(!empty($this->infoBooking->tour)) $typeBooking = 'Tour';
        if($this->typeEmail=='notice'){
            $addressMail        = config('company.email');
            $titleMail          = 'Thông báo booking '.$typeBooking.' số '.$this->infoBooking->no.' - '.date('H:i d/m/Y', time());
            $bodyMail           = view('admin.booking.confirmBooking', [
                'item'      => $this->infoBooking, 
                'infoStaff' => $this->infoStaff,
                'type'      => $this->typeEmail
            ]);
        }else {
            $addressMail        = $this->infoBooking->customer_contact->email;
            $titleMail          = config('company.webname').' - Xác nhận đặt '.$typeBooking.' số '.$this->infoBooking->no.' - '.date('H:i d/m/Y', time());
            $bodyMail           = view('admin.booking.confirmBooking', [
                'item' => $this->infoBooking, 
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
