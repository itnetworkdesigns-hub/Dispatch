<?php
require_once __DIR__ . '/Session.php';

class Auth
{
    public static function startSession()
    {
        Session::start();
    }

    public static function login(array $user)
    {
        self::startSession();
        // store minimal info
        Session::set('user', [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'] ?? null,
        ]);
    }

    public static function logout()
    {
        self::startSession();
        Session::remove('user');
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
    }

    public static function user()
    {
        self::startSession();
        return Session::get('user');
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
