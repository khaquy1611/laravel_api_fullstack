<?php 
return [
    'image' => [
        'disk' => 'public',
        'max_size' => 5120, // 5mb
        'allowed_mime_types' => [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/jpg'
        ],
        'base_path' => 'uploads',
        'pipelines' => [
            'default' => [
                'generate_filename' => [
                    'enabled' => true,
                ],
                'resize' => [
                    'enabled' => false,
                    'width' => 800,
                    'height' => 600
                ],
                'optimize' => [
                    'enabled' => false,
                    'quality' => 85
                ],
                'storage' => [
                    'enabled' => true
                ] 
            ]
                ],
            'avatar' => [
                'generate_filename' => [
                    'enabled' => true,
                ],
                'resize' => [
                    'enabled' => true,
                    'width' => 300,
                    'height' => 300
                ],
                'optimize' => [
                    'enabled' => true,
                    'quality' => 90
                ],
                'storage' => [
                    'enabled' => true
                ] 
                ],
    ]
    ];