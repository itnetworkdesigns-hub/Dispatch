<?php
class Session
{
    public static function start(): void
    {
        if (PHP_SAPI === 'cli') return;
        if (session_status() === PHP_SESSION_NONE) {
            if (!headers_sent()) session_start();
        }
    }

    public static function set(string $key, $value): void
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    public static function get(string $key, $default = null)
    {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    public static function remove(string $key): void
    {
        self::start();
        unset($_SESSION[$key]);
    }

    public static function destroy(): void
    {
        self::start();
        session_unset();
        session_destroy();
    }

    public static function id(): string
    {
        self::start();
        return session_id();
    }
}
