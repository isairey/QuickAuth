<?php

declare(strict_types=1);

namespace QuickAuth;

/**
 * Minimal session-backed OAuth state store.
 *
 * Call startSession() before using this class. In production, configure
 * session cookie flags before session_start() and use HTTPS.
 */
final class StateStore
{
    private const KEY = 'quickauth.oauth_states';

    public function create(string $provider): string
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            throw new \RuntimeException('PHP session must be active before creating OAuth state.');
        }

        $state = bin2hex(random_bytes(32));
        $_SESSION[self::KEY][$provider] = [
            'value' => $state,
            'created_at' => time(),
        ];

        return $state;
    }

    public function consume(string $provider, string $providedState, int $maxAgeSeconds = 600): bool
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            throw new \RuntimeException('PHP session must be active before validating OAuth state.');
        }

        $stored = $_SESSION[self::KEY][$provider] ?? null;
        unset($_SESSION[self::KEY][$provider]);

        if (!is_array($stored) || !isset($stored['value'], $stored['created_at'])) {
            return false;
        }

        if (!is_string($providedState) || !is_string($stored['value'])) {
            return false;
        }

        $age = time() - (int) $stored['created_at'];
        return $age >= 0
            && $age <= $maxAgeSeconds
            && hash_equals($stored['value'], $providedState);
    }
}
