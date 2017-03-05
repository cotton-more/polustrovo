<?php

return [
    'url'            => getenv('URL'),
    'browshot_key'   => getenv('BROWSHOT_KEY'),
    'screenshot_dir' => realpath(__DIR__.'/../screenshots'),
];