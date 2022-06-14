<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Response;

class ApiManager
{

    public function __construct()
    {
        
    }

    /**
     * Generate render response
     */
    public function generateResponse($data)
    {
        $response = new Response();
        $response->setContent(json_encode($data));
        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }

    public function post($url, $data, $mode = "prod"){

        if ($mode === "dev") {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        } else {
            $ch = curl_init($url);
        }

        if ($data != null) {
            curl_setopt_array($ch, array(
                CURLOPT_POST => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CONNECTTIMEOUT => 0,
                CURLOPT_TIMEOUT_MS => 0,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type:application/json' // "Content-Type:application/json", et non pas "Content-Type: application/json"
                ),
                CURLOPT_POSTFIELDS => json_encode($data)
            ));
        } else {
            curl_setopt_array($ch, array(
                CURLOPT_POST => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CONNECTTIMEOUT => 0,
                CURLOPT_TIMEOUT_MS => 1000,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type:application/json' // "Content-Type:application/json", et non pas "Content-Type: application/json"
                ),
            ));
        }

        $response = curl_exec($ch);

        if ($response === false) {
            return (curl_error($ch));
        }

        $responseData = json_decode($response, true);
        curl_close($ch);

        dd($response);

        return $responseData;
    }
    

}
