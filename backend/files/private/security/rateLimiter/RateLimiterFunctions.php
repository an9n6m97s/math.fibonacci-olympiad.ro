<?php
require_once 'RateLimiter.php';

function checkRateLimit(string $key = 'default', string $identifier = null): bool
{
    global $rateLimiter;

    if (!isset($rateLimiter)) {
        $rateLimiter = new RateLimiter();
    }
    return $rateLimiter->check($key, $identifier);
}
function addRateLimit(string $key, int $limit, int $period)
{
    global $rateLimiter;

    if (!isset($rateLimiter)) {
        $rateLimiter = new RateLimiter();
    }
    $rateLimiter->addLimit($key, $limit, $period);
}
function setRateLimits(array $limits)
{
    global $rateLimiter;

    if (!isset($rateLimiter)) {
        $rateLimiter = new RateLimiter();
    }
    $rateLimiter->setLimits($limits);
}
