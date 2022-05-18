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

        public function __construct(Client $request_client){

                $this->request_client = $request_client;
                $this->base_url = env('fatoorah_base_url');
                $this->headers= [
                    'Content_Type'=>'application/json',
                    'authorization'=>'Bearer '.env('fatoorah_token') //token
                    ];
        }


        public function buildRequest($uri , $method , $data = []){

            $request = new Request($method , $this->base_url.$uri , $this->headers);

//            if(!$data)
//                return false;

            $response = $this->request_client->send($request ,
                 ['json'=> $data] );

//            if($response->getStatusCode() != 200)
//                 return false;
           $response = json_decode($response->getBody() , true); //returns a url

            return $response;
        }


        public function sendPayment($data){
           return $this->buildRequest('/v2/SendPayment' , 'POST' , $data); //CONNECTION DETAILS
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

    public function getCallbackStatus(array $data)
    {
        return $this->buildRequest('/v2/getPaymentStatus' , 'POST' , $data); //CONNECTION DETAILS

    }

}
