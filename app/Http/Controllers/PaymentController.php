<?php

namespace App\Http\Controllers;

use App\Services\FatoorahServices;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    private $fatoorahServices;

    public function __construct(FatoorahServices $fatoorahServices)
    {
        $this->fatoorahServices = $fatoorahServices;
    }

    public function payOrder()
    {
        $data = [
            'NotificationOption' => 'Lnk',
            'paymentMethodId' => '',
            'InvoiceValue' => '50',
            'ErrorUrl' => 'https://www.facebook.com/',
            'CallBackUrl' => 'http://127.0.0.1:8000/api/paymentCallback',
            'CustomerName' => 'muhammad elalfy',
            'DisplayCurrencyIso' => 'SAR',
            'MobileCountryCode' => '+965',
            'CustomerMobile' => '1234567890',
            'CustomerEmail' => 'dev.muhamadelalfy@gmail.com',
            'Language' => 'en', //or 'ar'
            'CustomerReference' => 'orderId',
            'CustomerCivilId' => 'CivilId',
        ];
        return $this->fatoorahServices->sendPayment($data);
    }

    public function paymentCallback(Request $request)
    {
        $payment_id = $request->paymentId;
        $data = [];
        $data['key'] = $payment_id;
        $data['KeyType'] = 'PaymentId';

        return $this->fatoorahServices->getCallbackStatus($data);
    }
}
