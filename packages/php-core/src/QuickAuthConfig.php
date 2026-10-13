<?php

declare(strict_types=1);

namespace QuickAuth;

final readonly class QuickAuthConfig
{
    /**
     * @param array<string, array{client_id: string, client_secret: string, redirect_uri: string}> $providers
     */
    public function __construct(
        public array $providers,
        public string $baseUrl,
        public string $sessionName = 'quickauth_session',
    ) {
        foreach ($providers as $name => $config) {
            if (!in_array($name, [Provider::Google->value, Provider::Facebook->value], true)) {
                throw new \InvalidArgumentException("Unsupported provider: {$name}");
            }

            foreach (['client_id', 'client_secret', 'redirect_uri'] as $required) {
                if (!isset($config[$required]) || trim($config[$required]) === '') {
                    throw new \InvalidArgumentException("Missing {$required} for provider {$name}");
                }
            }

            if (!filter_var($config['redirect_uri'], FILTER_VALIDATE_URL)) {
                throw new \InvalidArgumentException("Invalid redirect_uri for provider {$name}");
            }
        }

        if (!filter_var($baseUrl, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException('baseUrl must be a valid URL');
        }
    }
}
