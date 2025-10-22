<?php
class RateLimiter
{
    private $limits = [
        'default' => [
            'limit' => 100,
            'period' => 60,
        ]
    ];
    private $storageKey = 'rate_limits';

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION[$this->storageKey])) {
            $_SESSION[$this->storageKey] = [];
        }
    }

    public function addLimit(string $key, int $limit, int $period)
    {
        $this->limits[$key] = [
            'limit' => $limit,
            'period' => $period,
        ];
    }
    public function setLimits(array $limits)
    {
        $this->limits = $limits;
    }
    public function getLimits()
    {
        return  $this->limits;
    }

    public function check(string $key = "default", string $identifier = null): bool
    {
        if (!$identifier) {
            $identifier = $this->getIpAddress();
        }

        if (!isset($_SESSION[$this->storageKey][$key])) {
            $_SESSION[$this->storageKey][$key] = [];
        }

        if (!isset($_SESSION[$this->storageKey][$key][$identifier])) {
            $_SESSION[$this->storageKey][$key][$identifier] = ['requests' => 0, 'last_request_time' => time()];
            return true;
        }

        $data =  $_SESSION[$this->storageKey][$key][$identifier];

        $limitData = $this->limits[$key] ?? $this->limits['default'];


        $limit = $limitData['limit'];
        $period = $limitData['period'];


        if (time() - $data['last_request_time'] > $period) {
            $_SESSION[$this->storageKey][$key][$identifier] = ['requests' => 1, 'last_request_time' => time()];
            return true;
        }

        if ($data['requests'] >= $limit) {
            return false;
        }


        $_SESSION[$this->storageKey][$key][$identifier]['requests']++;
        $_SESSION[$this->storageKey][$key][$identifier]['last_request_time'] = time();


        return true;
    }


    public function getIpAddress(): string
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        return $_SERVER['REMOTE_ADDR'];
    }
}
