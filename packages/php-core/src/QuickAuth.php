<?php

declare(strict_types=1);

namespace QuickAuth;

/**
 * Initial facade. Provider-specific authorization URL creation and callback
 * exchange are intentionally not implemented yet; do not treat this as
 * production-ready authentication.
 */
final class QuickAuth
{
    public function __construct(
        private readonly QuickAuthConfig $config,
        private readonly StateStore $states = new StateStore(),
    ) {}

    public function begin(string $provider): never
    {
        $providerEnum = Provider::tryFrom($provider);
        if ($providerEnum === null || !isset($this->config->providers[$provider])) {
            throw new \InvalidArgumentException('Provider is unsupported or not configured.');
        }

        Session::start($this->config->sessionName);
        $state = $this->states->create($provider);

        // Placeholder: next milestone will construct the provider authorization URL
        // and redirect there. State is generated and stored here, but no OAuth flow
        // should be considered complete until the adapter is implemented.
        throw new \LogicException(
            sprintf('OAuth adapter for %s is not implemented yet (state generated: %s).', $provider, $state)
        );
    }

    public function validateCallbackState(string $provider, string $state): bool
    {
        Session::start($this->config->sessionName);
        return $this->states->consume($provider, $state);
    }
}
