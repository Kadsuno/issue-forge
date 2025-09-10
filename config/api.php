<?php

declare(strict_types=1);

return [
    'version' => env('API_VERSION', 'v1'),

    // Admin bearer token for API-only access (non-user-bound). Rotate regularly.
    'admin_token' => env('API_ADMIN_TOKEN', ''),
];


