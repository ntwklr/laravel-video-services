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
            ]
        ],
        'vimeo' => [
            'urls' => [
                'vimeo.com'
            ]
        ]
    ],

    'router'         => [
        'attributes' => [
            'prefix'     => '_video-services',

            'middleware' => env('NTWKLR_VIDEOSERVICES_MIDDLEWARE') ? explode(',', env('NTWKLR_VIDEOSERVICES_MIDDLEWARE')) : null,
        ],
    ],
];
