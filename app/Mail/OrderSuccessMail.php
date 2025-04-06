<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public $orderInfo;
    public $customerEmail;

    // Modify the constructor to accept both order info and customer email
    public function __construct($orderInfo, $customerEmail)
    {
        $this->orderInfo = $orderInfo;
        $this->customerEmail = $customerEmail; // Store the customer email
    }

    public function build()
    {
        return $this->from('no-reply@shoplocalbrand.test')
            ->to($this->customerEmail)  // Now it uses the customer email
            ->subject('Đơn hàng của bạn đã được xác nhận')
            ->view('emails.order_success');
    }
}
