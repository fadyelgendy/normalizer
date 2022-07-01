<?php

class Session
{
    public function __construct($key, $value)
    {
        session_start();

        $_SESSION[$key] = $value;
    }

    /**
     * Put key => value into session
     *
     * @param string $key
     * @param string $value
     * @return boolean
     */
    public function put(string $key, string $value): bool
    {
        if (empty($key) || empty($value))
            return false;

        $_SESSION[$key] = $value;

        return true;
    }

    /**
     * Get Session value with a given key
     *
     * @param string $key
     * @return string|null
     */
    public function get(string $key): ?string
    {
        if (!array_key_exists($key, $_SESSION))
            return null;

        return $_SESSION[$key];
    }
}