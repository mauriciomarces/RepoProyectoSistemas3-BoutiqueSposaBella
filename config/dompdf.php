<?php

return [
    'show_warnings' => false,

    'public_path' => null,

    'convert_entities' => true,

    'options' => [
        'defaultFont' => 'Playfair Display',
        'fontDir' => storage_path('fonts/'),
        'fontCache' => storage_path('fonts/'),
        'tempDir' => storage_path('fonts/'),
        'chroot' => realpath(base_path()),
        'isPhpEnabled' => false,
        'isRemoteEnabled' => true,
        'dpi' => 96,
    ],

    'font_dir' => storage_path('fonts'),
    'font_cache' => storage_path('fonts'),
    'temp_dir' => storage_path('fonts'),
    'chroot' => realpath(base_path()),
    'log_output_file' => null,
    'enable_font_subsetting' => true,
];
