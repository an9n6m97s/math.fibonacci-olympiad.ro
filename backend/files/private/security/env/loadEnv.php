<?php

declare(strict_types=1);
class env
{
    private static bool $loaded = false;
    private const FILE  = '/.env';
    private const DB_KEYS = ['DB_HOST', 'DB_PORT', 'DB_NAME', 'DB_USER', 'DB_PASSWORD'];

    /**
     * Loads only DB_* variables (DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PASSWORD)
     * into getenv/$_ENV/$_SERVER without calling init() and without $settings checks.
     */
    public static function loadDatabase(): void
    {
        $file = $_SERVER['DOCUMENT_ROOT'] . self::FILE;
        if (!is_readable($file)) {
            throw new RuntimeException("Environment file not found or unreadable: {$file}");
        }

        $found = [];
        $fp = new SplFileObject($file);
        while (!$fp->eof()) {
            $line = trim($fp->fgets());
            if ($line === '' || $line[0] === '#') {
                continue;
            }
            [$key, $value] = explode('=', $line, 2) + [null, null];
            if ($key === null || $value === null) {
                continue;
            }
            $key   = trim($key,   " \t\n\r\0\x0B\"'");
            $value = trim($value, " \t\n\r\0\x0B\"'");
            if ($key === '' || !in_array($key, self::DB_KEYS, true)) {
                continue;
            }
            $found[$key] = $value;
        }

        foreach ($found as $k => $v) {
            putenv("{$k}={$v}");
            $_ENV[$k]    = $v;
            $_SERVER[$k] = $v;
        }
    }

    /**
     * Removes the DB_* variables previously loaded by loadDatabase().
     */
    public static function unloadDatabase(): void
    {
        foreach (self::DB_KEYS as $k) {
            putenv($k);
            unset($_ENV[$k], $_SERVER[$k]);
        }
    }

    /**
     * Returns the current DB_* variables from the environment.
     */
    public static function getDatabase(): array
    {
        $out = [];
        foreach (self::DB_KEYS as $k) {
            $out[$k] = getenv($k) !== false ? getenv($k) : null;
        }
        return $out;
    }

    /**
     * Initializes the environment by loading and validating the security token from the environment file.
     */
    private static function init(): void
    {
        global $settings;
        $file = $_SERVER['DOCUMENT_ROOT'] . self::FILE;

        if (!is_readable($file)) {
            sendNotify('Security Token Missing', 'Environment file not found or unreadable.');
            if (!page('maintenance')) {
                header('Location: /maintenance');
                exit();
            }
        }

        $fp = new SplFileObject($file);
        do {
            $line = trim($fp->fgets());
        } while (!$fp->eof() && ($line === '' || $line[0] === '#'));

        [$k, $v] = explode('=', $line, 2) + [null, null];
        $token   = trim($v ?? '', " \t\n\r\0\x0B\"'");

        if (($settings['securityToken'] ?? '') !== $token) {
            sendNotify(
                'Security Token Mismatch',
                'The security token does not match the expected value. Please check your configuration.'
            );
            if (!page('maintenance')) {
                header('Location: /maintenance');
                exit();
            }
            exit();
        }
    }

    /**
     * Loads all environment variables from the file (after init validation).
     */
    public static function loadEnv(bool $forceReload = false): void
    {
        if (self::$loaded && !$forceReload) {
            return;
        }

        self::init();

        $file = $_SERVER['DOCUMENT_ROOT'] . self::FILE;
        if (!is_readable($file)) {
            throw new RuntimeException("Environment file not found or unreadable: {$file}");
        }

        $fp = new SplFileObject($file);
        while (!$fp->eof()) {
            $line = trim($fp->fgets());
            if ($line === '' || $line[0] === '#') {
                continue;
            }

            [$key, $value] = explode('=', $line, 2) + [null, null];
            if ($key === null || $value === null) {
                continue;
            }

            $key   = trim($key,   " \t\n\r\0\x0B\"'");
            $value = trim($value, " \t\n\r\0\x0B\"'");

            if ($key === '') {
                continue;
            }

            putenv("{$key}={$value}");
            $_ENV[$key]    = $value;
            $_SERVER[$key] = $value;
        }

        self::$loaded = true;
    }

    /**
     * Forces a reload of all environment variables.
     */
    public static function hardReload(): void
    {
        self::$loaded = false;
        self::loadEnv(true);
    }

    /**
     * Unloads all environment variables loaded by loadEnv().
     */
    public static function unloadEnv(): void
    {
        $file = $_SERVER['DOCUMENT_ROOT'] . self::FILE;

        if (!is_readable($file)) {
            throw new RuntimeException("Environment file not found or unreadable: {$file}");
        }

        $fp = new SplFileObject($file);
        while (!$fp->eof()) {
            $line = trim($fp->fgets());
            if ($line === '' || $line[0] === '#') {
                continue;
            }

            [$key, $value] = explode('=', $line, 2) + [null, null];
            if ($key === null) {
                continue;
            }

            $key = trim($key, " \t\n\r\0\x0B\"'");
            if ($key === '') {
                continue;
            }

            putenv($key);
            unset($_ENV[$key], $_SERVER[$key]);
        }

        self::$loaded = false;
    }
}
