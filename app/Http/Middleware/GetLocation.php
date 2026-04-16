<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class GetLocation
{
    /**
     * Geolocation and currency exchange logic.
     * Ported from perfume_admin.
     */
    public function handle(Request $request, Closure $next)
    {
        if (empty(Session::get('locationdata'))) {
            // Default IP used for testing/fallback as seen in reference
            $clientIP = '80.227.51.158'; 
            
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://ipxapi.com/api/ip?ip=" . $clientIP,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_HTTPHEADER => array(
                    "Accept: application/json",
                    "Authorization: Bearer 3775|WiZoQdLd18pDu6Ra1AL5LaLynBYhdw093VrXOchb",
                    "Content-Type: application/json",
                    "cache-control: no-cache"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if (!$err) {
                $data = json_decode($response);
                if (isset($data->city)) {
                    Session::put('locationdata', $response);
                    Session::put('locationdatacity', $data->city);
                    Session::put('locationdatacurrency', $data->currencyCode);
                }
            }
        }

        # Handling currency rate conversion
        if (empty(Session::get("currencyrate")) && !empty(Session::get('locationdatacurrency'))) {
            $currencyCode = Session::get('locationdatacurrency');
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.apilayer.com/exchangerates_data/convert?to=$currencyCode&from=aed&amount=1",
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: text/plain",
                    "apikey: h06MiV6h7JE2FmfqgjqD5v4mvlGmYFrU"
                ),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET"
            ));

            $response = curl_exec($curl);
            curl_close($curl);
            $dataa = json_decode($response);
            
            if (isset($dataa->success) && $dataa->success) {
                Session::put("currencyrate", $dataa->result);
                Session::put("currency", $currencyCode);
            }
        }

        return $next($request);
    }
}
