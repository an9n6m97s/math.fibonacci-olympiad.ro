<?php
class CsrfToken
{
    private $sessionKey = 'csrf_tokens';
    private $tokenLifetime = 60 * 60;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION[$this->sessionKey])) {
            $_SESSION[$this->sessionKey] = [];
        }
    }

    private function generateToken(): string
    {
        return bin2hex(random_bytes(32));
    }


    public function generateTokenForForm(string $formId): string
    {
        $token = $this->generateToken();
        $_SESSION[$this->sessionKey][$formId] = [
            'token' => $token,
            'timestamp' => time()
        ];
        return $token;
    }


    public function getFormToken(string $formId): ?string
    {
        if (isset($_SESSION[$this->sessionKey][$formId]) && $this->isTokenValid($formId)) {
            return  $_SESSION[$this->sessionKey][$formId]['token'];
        }
        return null;
    }


    public function validateToken(string $formId, string $token): bool
    {
        if (!isset($_SESSION[$this->sessionKey][$formId])) {
            return false;
        }

        if ($this->isTokenValid($formId) && hash_equals($_SESSION[$this->sessionKey][$formId]['token'], $token)) {
            return true;
        }
        return false;
    }
    private function isTokenValid(string $formId): bool
    {
        if (!isset($_SESSION[$this->sessionKey][$formId])) {
            return false;
        }

        $timestamp = $_SESSION[$this->sessionKey][$formId]['timestamp'];
        if (time() - $timestamp > $this->tokenLifetime) {
            unset($_SESSION[$this->sessionKey][$formId]);
            return false;
        }
        return true;
    }
}
