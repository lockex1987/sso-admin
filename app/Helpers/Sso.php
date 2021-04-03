<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Log;

class Sso
{
    public static function checkLoginCallback()
    {
        return Sso::checkSsoTicket('/check-login-ticket');
    }


    private static function checkSsoTicket($path)
    {
        $consumerDomain = config('services.sso.consumerDomain');
		$passportUrl = config('services.sso.passportUrl');

        $request = request();
        $ticket = $request->input('ticket');

        $url = $passportUrl . $path;
        $params = [
            // 'app' => $consumerDomain,
            // 'client_secret' => 'DjYOrAeOU4WxGtxacPpnmj96Ne53r5JAaU5EeIeP',
            'ticket' => $ticket
        ];
        $http = new Client();
		$response = $http->post($url, [
			'form_params' => $params
        ]);
        $responseBody = (string) $response->getBody();
        $obj = json_decode($responseBody);
        
        
        // https://laravel.com/docs/master/http-client
        /*
        $response = Http::post($url, $params);
        $obj = json_decode($response->body());
        */
        
        return $obj;
    }
}
