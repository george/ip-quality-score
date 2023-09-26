<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

include_once("../util/LoadedList.php");
include_once("../util/IPAddressRange.php");

$cinscore_list = new LoadedList("https://cinsscore.com/list/ci-badguys.txt");
$datacenters = new LoadedList("https://raw.githubusercontent.com/client9/ipcat/master/datacenters.csv");

$datacenters->map(function($line) {
    if (!str_contains($line, ",")) {
        return null;
    }

    $parts = explode(",", $line);

    $object = [];

    try {
        $object["range"] = new IPAddressRange($parts[0], $parts[1]);
    } catch (Exception) {
        echo $line;
        return null;
    }

    $object["isp"] = $parts[2];

    return $object;
});

function check_vpn(string $ipAddress):bool {
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, "https://blackbox.ipinfo.app/lookup/" . $ipAddress);
    curl_setopt($curl, CURLOPT_USERAGENT, "IP Fraud Score Checking");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($curl);
    curl_close($curl);

    return str_contains("Y", $result);
}

function getFraudScore(string $ipAddress):int {
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, "https://scamalytics.com/ip/$ipAddress");
    curl_setopt($curl, CURLOPT_USERAGENT, "IP Fraud Score Checking");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($curl);
    curl_close($curl);

    return (int) explode("</div> ", explode("Fraud Score: ", $result)[1])[0];
}

Route::get('check', function (Request $request, Response $response) use ($datacenters, $cinscore_list) {
    $ip = $request->ip() == "127.0.0.1" ? "12.19.29.29" : $request->ip();
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, "https://ipapi.co/$ip/json/");
    curl_setopt($curl, CURLOPT_USERAGENT, "Hostile API");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $result = json_decode(curl_exec($curl), true);
    curl_close($curl);

    $vpn = $datacenters->query_mapped_results(function ($line) use ($ip) {
        return $line != null && $line["range"]->contains($ip);
    }) != null;

    if (!$vpn) {
        $isp = strtolower($result['org']);

        if (str_contains($isp, "-")) {
            $isp = explode('-', $isp)[0];
        }

        $vpn = ($datacenters->query_mapped_results(function ($line) use ($isp) {
            return $line != null && str_contains($line["isp"], $isp);
        }) != null);
    }

    if (!$vpn) {
        $vpn = check_vpn($ip);
    }

    return $response->setStatusCode(201)
        ->header("Content-Type", "application/json")
        ->setContent(json_encode((object) [
            "ip" => $ip,
            "geolocationInfo" => $result,
            "cinscoreFlagged" => $cinscore_list->contains($ip),
            "fraudScore" => getFraudScore($ip),
            "vpn" => $vpn
        ]));
});
