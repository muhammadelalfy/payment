<?php

namespace App\Services;

use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Collection;
use Illuminate\Session\SessionManager;
class FatoorahServices {
    private $request_client;
    private $headers;
    private $base_url;
        public function construct(Client $request_client){

                $this->$request_client = $request_client;
                $this->base_url = env('fatoorah_base_url');
                $this->headers=
                    [
                    'Content_Type'=>'application/json',
                    'autherization'=>'Bearer'.env('fatoorah_token') //token
                    ];
        }

        public function buildRequest($uri , $method , $body = []){

            $request = new Request($method , $this->base_url.$uri , $this->headers);

            if(!$body)
                return false;

             $response = $this->request_client->send($request ,
                 ['json'=> $body] );

             if($response->getStatusCode() != 200)
                 return false;
             $response = json_decode($response->getBody() , true);

            return $response;
        }
        public function sendPayment($patient_id , $fee , $plan_id , $subscriptionPlan){
            $requestData = $this->parsePaymentData();
            $responce = $this->buildRequest('v2/SendPayment' , 'Post' , $requestData );
                if ($responce) {
                    $this->saveTransActionPayment($patient_id, ['Data']['Invoice_Id']);
                }
            return $responce;
        }
        public function parsePaymentData($user_id){
                $user = User::find($user_id);
                return [
                    'CustomerName'       => 'fname lname',
                    'DisplayCurrencyIso' => 'KWD',
                    'MobileCountryCode'  => '+965',
                    'CustomerMobile'     => '1234567890',
                    'CustomerEmail'      => 'email@example.com',
                    'Language'           => 'en', //or 'ar'
                    'CustomerReference'  => 'orderId',
                    'CustomerCivilId'    => 'CivilId',
                ];
        }

}
