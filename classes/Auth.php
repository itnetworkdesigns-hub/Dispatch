<?php
require_once __DIR__ . '/Session.php';
require_once __DIR__ . '/../config.php';

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

    public static function isAdmin(): bool
    {
        $u = self::user();
        if (!$u) return false;
        try {
            $db = getDB();
            $stmt = $db->prepare('SELECT is_admin FROM users WHERE id = :id LIMIT 1');
            $stmt->execute([':id' => $u['id']]);
            $row = $stmt->fetch();
            return !empty($row['is_admin']);
        } catch (Exception $e) {
            return false;
        }
    }

    public static function requireAdmin()
    {
        if (!self::isAdmin()) {
            http_response_code(403);
            exit('Forbidden - admin only');
        }
    }

    public static function isApproved(): bool
    {
        $u = self::user();
        if (!$u) return false;
        try {
            $db = getDB();
            $stmt = $db->prepare('SELECT is_approved FROM users WHERE id = :id LIMIT 1');
            $stmt->execute([':id' => $u['id']]);
            $row = $stmt->fetch();
            return !empty($row['is_approved']);
        } catch (Exception $e) {
            return false;
        }
    }

    public static function requireApproved()
    {
        if (!self::isApproved()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'error' => 'Account not approved by admin']);
            exit;
        }
    }
}
