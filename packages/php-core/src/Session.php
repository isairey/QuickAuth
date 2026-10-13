<?php

declare(strict_types=1);

namespace QuickAuth;

final class Session
{
    public static function start(string $name = 'quickauth_session'): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        if (headers_sent()) {
            throw new \RuntimeException('Cannot start session: response headers have already been sent.');
        }

        session_name($name);
        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'secure' => self::isHttps(),
            'httponly' => true,
            'samesite' => 'Lax',
        ]);

        if (!session_start()) {
            throw new \RuntimeException('Unable to start PHP session.');
        }
    }

    public static function regenerate(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            throw new \RuntimeException('Session must be active before regeneration.');
        }

        if (!session_regenerate_id(true)) {
            throw new \RuntimeException('Unable to regenerate session ID.');
        }
    }

    private static function isHttps(): bool
    {
        return isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== '' && $_SERVER['HTTPS'] !== 'off';
    }
}
