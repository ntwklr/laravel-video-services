<?php

return [
    'services' => [
        'youtube' => [
            'urls' => [
                'youtube.com',
                'youtu.be'
            ],
            'api' => [
                'url' => 'https://www.googleapis.com/youtube/v3/',
                'key' => env('YT_KEY', 'your-api-key'),
                'timeout' => 30
            ],
            'thumbnails' => [
                'url' => 'https://i.ytimg.com/vi/',
                'sizes' => [
                    'default' => 'default.jpg',
                    'medium' => 'mqdefault.jpg',
                    'high' => 'hqdefault.jpg',
                    'standard' => 'sddefault.jpg',
                    'maxres' => 'maxresdefault.jpg'
                ],
                'timeout' => 30,
            ]
        ],
        'vimeo' => [
            'urls' => [
                'vimeo.com'
            ]
        ]
    ],

    'storage' => [
        'path' => storage_path("app/video-services"),
    ],

    'cache' => [
        'prefix' => 'ntwklr:videoservices',
        'ttl' => (60 * 60 * 24)
    ],

    'router'         => [
        'attributes' => [
            'prefix'     => '_video-services',

            'middleware' => env('NTWKLR_VIDEOSERVICES_MIDDLEWARE') ? explode(',', env('NTWKLR_VIDEOSERVICES_MIDDLEWARE')) : null,
        ],
    ],
];
