<?php

return [
    'editor' => env('OPEN_ON_MAKE_EDITOR', get_cfg_var('open_on_make_editor') ?: 'subl'),
    'flags' => env('OPEN_ON_MAKE_FLAGS', '')
];