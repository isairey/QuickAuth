<?php

declare(strict_types=1);

namespace QuickAuth;

enum Provider: string
{
    case Google = 'google';
    case Facebook = 'facebook';
}
