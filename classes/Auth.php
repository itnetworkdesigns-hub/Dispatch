<?php

class Auth
{
    public static function startSession()
    {
        // Avoid starting sessions in CLI and protect against headers already sent
        if (PHP_SAPI === 'cli') {
            return;
        }

        if (session_status() === PHP_SESSION_NONE) {
            if (!headers_sent()) {
                session_start();
            }
        }
    }

    public static function login(array $user)
    {
        self::startSession();
        // store minimal info
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'] ?? null,
        ];
    }

    public static function logout()
    {
        self::startSession();
        if (session_status() === PHP_SESSION_ACTIVE) {
            unset($_SESSION['user']);
            session_regenerate_id(true);
        } else {
            // ensure session user cleared in case session isn't active
            if (isset($_SESSION['user'])) {
                unset($_SESSION['user']);
            }
        }
    }

    public static function user()
    {
        self::startSession();
        return $_SESSION['user'] ?? null;
    }

    public static function check()
    {
        return (bool)self::user();
    }

    public static function requireRole(string $role)
    {
        $u = self::user();
        if (!$u || ($u['role'] ?? null) !== $role) {
            http_response_code(403);
            exit('Forbidden');
        }
    }
}
