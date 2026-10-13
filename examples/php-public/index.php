<?php

declare(strict_types=1);

// Example routing sketch only. Do not expose provider secrets in frontend code.
// Complete OAuth adapters and callback handling are the next implementation step.

require __DIR__ . '/../../packages/php-core/vendor/autoload.php';

use QuickAuth\QuickAuth;
use QuickAuth\QuickAuthConfig;

$config = new QuickAuthConfig(
    providers: [
        // Fill from server environment variables after configuring OAuth apps.
        // 'google' => [
        //     'client_id' => getenv('QUICKAUTH_GOOGLE_CLIENT_ID') ?: '',
        //     'client_secret' => getenv('QUICKAUTH_GOOGLE_CLIENT_SECRET') ?: '',
        //     'redirect_uri' => getenv('QUICKAUTH_GOOGLE_REDIRECT_URI') ?: '',
        // ],
    ],
    baseUrl: getenv('QUICKAUTH_BASE_URL') ?: 'http://localhost:8000',
);

$auth = new QuickAuth($config);

http_response_code(501);
header('Content-Type: application/json; charset=utf-8');
echo json_encode([
    'status' => 'not_implemented',
    'message' => 'OAuth provider adapters and callback endpoints are not implemented yet.',
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
