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

}
