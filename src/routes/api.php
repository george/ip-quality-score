<?php

require_once __DIR__ . '/../helpers/IpQualityHelper.php';

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

use Helpers\IpQualityHelper;

Route::get('check', function (Request $request, Response $response) {
    $ip = $request->ip() == "127.0.0.1" || "0.0.0.0" ? "12.19.29.29" : $request->ip();
    $curl = IpQualityHelper\get_curl_handle("https://ipapi.co/$ip/json/");

    $result = json_decode(curl_exec($curl), true);

    curl_close($curl);

    $vpn = IpQualityHelper\is_ip_datacenter_range($ip) ||
        IpQualityHelper\is_isp_datacenter(strtolower($result['org'])) ||
        IpQualityHelper\check_vpn($ip);

    $cinscore_flagged = IpQualityHelper\get_cinscore_flagged($ip);
    $fraud_score = IpQualityHelper\get_fraud_score($ip);

    return $response->setStatusCode(201)
        ->header("Content-Type", "application/json")
        ->setContent(json_encode((object) [
            "ip" => $ip,
            "geolocationInfo" => $result,
            "cinscoreFlagged" => $cinscore_flagged,
            "fraudScore" => $fraud_score,
            "vpn" => $vpn
        ]));
});
