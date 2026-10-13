<?php

declare(strict_types=1);

namespace QuickAuth;

final readonly class AuthenticatedUser
{
    public function __construct(
        public string $provider,
        public string $providerUserId,
        public ?string $name,
        public ?string $email,
        public ?string $avatarUrl,
    ) {}
}
