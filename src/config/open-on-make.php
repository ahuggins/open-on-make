<?php

return [
    'editor' => env('OPEN_ON_MAKE_EDITOR', 'code'),
    'flags' => env('OPEN_ON_MAKE_FLAGS', ''),
    'enabled' => env('OPEN_ON_MAKE_ENABLED', true),
    /**
     * Command name prefixes that generate files and should trigger
     * opening, e.g. the saloon:* commands from saloonphp/laravel-plugin.
     */
    'prefixes' => ['make:', 'saloon:'],
    /**
     * Add here your custom paths.
     */
    'paths' => []
];
