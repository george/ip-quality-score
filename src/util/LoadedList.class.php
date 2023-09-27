<?php

class LoadedList
{

    protected array $entries = array();
    protected array $mapped = array();

    public function __construct(string $url)
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($curl);

        curl_close($curl);

        foreach (explode("\n", $result) as $line) {
            $this->entries[] = strtolower($line);
        }
    }

    public function map($mapping_function): void
    {
        foreach ($this->entries as $entry) {
            $this->mapped[] = $mapping_function($entry);
        }
    }

    public function query_mapped_results($query_function): array|null
    {
        foreach ($this->mapped as $mapped_entry) {
            if ($query_function($mapped_entry)) {
                return $mapped_entry;
            }
        }

        return null;
    }

    public function contains(string $element): bool
    {
        return in_array(strtolower($element), $this->entries);
    }
}
