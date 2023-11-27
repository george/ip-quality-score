<?php

namespace Helpers\IpQualityHelper {

    require_once __DIR__ . '/../util/LoadedList.class.php';
    require_once __DIR__ . '/../util/IPAddressRange.class.php';

    use Exception;
    use LoadedList;
    use IPAddressRange;

    const CINSCORE_LIST = new LoadedList("https://cinsscore.com/list/ci-badguys.txt");
    const DATACENTERS = new LoadedList("https://raw.githubusercontent.com/client9/ipcat/master/datacenters.csv");

    DATACENTERS->map(function($line) {
        if (!str_contains($line, ",")) {
            return null;
        }

        $parts = explode(",", $line);

        $object = [];

        try {
            $object["range"] = new IPAddressRange($parts[0], $parts[1]);
        } catch (Exception $exception) {
            error_log($exception);
            return null;
        }

        $object["isp"] = $parts[2];

        return $object;
    });

    function check_vpn(string $ipAddress):bool {
        $curl = get_curl_handle("https://blackbox.ipinfo.app/lookup/" . $ipAddress);
        $result = curl_exec($curl);
        curl_close($curl);

        return str_contains("Y", $result);
    }

    function get_fraud_score(string $ipAddress):int {
        $curl = get_curl_handle("https://scamalytics.com/ip/$ipAddress");
        $result = curl_exec($curl);
        curl_close($curl);

        return (int) explode("</div> ", explode("Fraud Score: ", $result)[1])[0];
    }

    function get_curl_handle(string $url):\CurlHandle {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT, "IP Fraud Score Checking");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        return $curl;
    }

    function is_ip_datacenter_range($ip):bool {
        return DATACENTERS->query_mapped_results(function ($line) use ($ip) {
            return $line != null && $line["range"]->contains($ip);
        }) != null;
    }

    function is_isp_datacenter($isp):bool {
        if (str_contains($isp, "-")) {
            $isp = explode('-', $isp)[0];
        }

        return (DATACENTERS->query_mapped_results(function ($line) use ($isp) {
            return $line != null && str_contains($line["isp"], $isp);
        }) != null);
    }

    function get_cinscore_flagged($ip):bool {
        return CINSCORE_LIST->contains($ip);
    }
}
