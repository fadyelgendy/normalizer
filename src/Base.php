<?php

class Base
{
    /**
     * Load .env file, and extract data from it
     *
     * @return array
     */
    public function loadEnv(): array
    {
        $env  = file_get_contents(dirname(__DIR__) . "/.env");
        $env_exploded = preg_split("/\n/", $env);

        $data = [];
        foreach ($env_exploded as $key => $entry) {
            $temp = explode("=", $entry);

            if (count($temp) == 2) {
                $key = trim($temp[0]);
                $value = trim($temp[1]);

                $data[$key] = $value;
            }
        }

        return $data;
    }

    /**
     * return a env value, for a given key or null
     *
     * @param string $key
     * @return null|string
     */
    public function getEnvValue(string $key): ?string
    {
        return $this->loadEnv()[strtoupper($key)];
    }
}
