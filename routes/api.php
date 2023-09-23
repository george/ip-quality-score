<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

$cinscoreIps = array();

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, "https://cinsscore.com/list/ci-badguys.txt");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$result = curl_exec($curl);
curl_close($curl);

foreach (explode("\n", $result) as $line) {
    $cinscoreIps[] = $line;
}

function getFraudScore(string $ipAddress):int {
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, "https://scamalytics.com/ip/$ipAddress");
    curl_setopt($curl, CURLOPT_USERAGENT, "Hostile API");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($curl);
    curl_close($curl);

    return (int) explode("</div> ", explode("Fraud Score: ", $result)[1])[0];
}

Route::get('check', function (Request $request, Response $response) use ($cinscoreIps) {
    $ip = $request->ip() == "127.0.0.1" ? "12.19.29.29" : $request->ip();
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, "https://ipapi.co/$ip/json/");
    curl_setopt($curl, CURLOPT_USERAGENT, "Hostile API");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $result = json_decode(curl_exec($curl));
    curl_close($curl);

    return $response->setStatusCode(201)
        ->header("Content-Type", "application/json")
        ->setContent(json_encode((object) [
            "ip" => $ip,
            "geolocationInfo" => $result,
            "cinscoreFlagged" => in_array($ip, $cinscoreIps),
            "fraudScore" => getFraudScore($ip)
        ]));
});
