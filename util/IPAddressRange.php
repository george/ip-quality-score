<?php

class IPAddressRange {

    private array $min_range;
    private array $max_range;

    /**
     * @throws Exception if the IP ranges are invalid
     */
    public function __construct(string $min_range, string $max_range) {
        if (sizeof(explode(".", $min_range)) != 4 || sizeof(explode(".", $max_range)) != 4) {
            throw new Exception("IP address must have 4 octets!");
        }

        $this->min_range = $this->map_to_array($min_range);
        $this->max_range = $this->map_to_array($max_range);
    }

    private function map_to_array(string $ip_address):array {
        return array_map(function ($number) {
            return intval($number);
        }, explode(".", $ip_address));
    }

    public function contains(string $ip_address):bool {
        $current_parts = $this->map_to_array($ip_address);

        if (sizeof($current_parts) != 4) {
            throw new InvalidArgumentException("IP address must have 4 octets!");
        }

        for ($i = 0; $i < 4; $i++) {
            $current = $current_parts[$i];

            $min = $this->min_range[$i];
            $max = $this->max_range[$i];

            if ($current < $min || $current > $max) {
                return false;
            }
        }

        return true;
    }
}
