<?php

return [
    'editor' => env('OPEN_ON_MAKE_EDITOR', 'subl'),
    'flags' => env('OPEN_ON_MAKE_FLAGS', ''),
    'enabled' => env('OPEN_ON_MAKE_ENABLED', true),
    /**
     * Add here your custom paths.
     */
    'paths' => []
];
