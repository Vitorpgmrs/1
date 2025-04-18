<?php
declare(strict_types=1);

class SessionManager {
    public static function start(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start([
                'cookie_lifetime' => 86400, // 1 dia
                'cookie_secure' => APP_ENV === 'production',
                'cookie_httponly' => true,
                'use_strict_mode' => true
            ]);
        }
    }

    public static function generateCsrfToken(): string {
        self::start();
        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_tokens'][$token] = time() + CSRF_TOKEN_LIFETIME;
        return $token;
    }

    public static function validateCsrfToken(string $token): bool {
        self::start();
        return isset($_SESSION['csrf_tokens'][$token]) && 
               $_SESSION['csrf_tokens'][$token] >= time();
    }
}
function isUserLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

function getLoggedInUserId(): ?int {
    return $_SESSION['user_id'] ?? null;
}

function loginUser(int $userId): void {
    $_SESSION['user_id'] = $userId;
}

function logoutUser(): void {
    session_destroy();
}
