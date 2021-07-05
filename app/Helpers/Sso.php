<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class Sso
{
    /**
     * Kiểm tra thông tin đăng nhập sau khi sso-passport trả về code.
     */
    public static function checkLoginCallback()
    {
        return Sso::checkSsoTicket('/api/check-login-ticket');
    }

    /**
     * Kiểm tra code (ticket) từ sso-passport.
     */
    private static function checkSsoTicket($path)
    {
        $consumerDomain = config('services.sso.consumerDomain');
        $passportUrl = config('services.sso.passportUrl');

        $request = request();
        $ticket = $request->ticket;

        $url = $passportUrl . $path;
        $params = [
            // 'app' => $consumerDomain,
            // 'client_secret' => 'DjYOrAeOU4WxGtxacPpnmj96Ne53r5JAaU5EeIeP',
            'ticket' => $ticket
        ];

        // Khi kết nối HTTPS có thể bị lỗi "cURL error 60: SSL certificate problem: unable to get local issuer certificate"
        // Cách 1: Disable cert verification
        // Cách 2: Sử dụng file cert
        $http = new Client([
            'verify' => false
            // 'verify' => '/path/to/cacert.pem'
        ]);

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
