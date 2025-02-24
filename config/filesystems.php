<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been set up for each driver as an example of the required values.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
            'throw' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
            'throw' => false,
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
        ],

        'gcs' => [
            'driver' => 'gcs',
            // 'key_file_path' => env('GOOGLE_CLOUD_KEY_FILE', base_path('credentials.json')),
            'key_file' => [
                "type"=> "service_account",
                "project_id"=> "name-382617",
                "private_key_id"=> "b3d9912a7ce10efd3edead8b50a594ae6b3237a5",
                "private_key"=> "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDcxqAdMFsAexHm\nt8O7qaM+Fl4xU76QG+DsGLYsGRZRDqobN64QUOTZSnjhsBN8i2kajyZgsg9IybF3\nFPrj+EyNNQKk4qbANVGRC+ITcCLE5UeoLn3UwfGWyoRCR8Okvn8C5a56teuNI+o9\nLm7iEkqABGkcL+2cqw1yLL3/yBfQSubI4uG5/SHCXrY3wGOv26Wh2JPGfMm4LU4p\nmyUQ3ZooADsiUtoYAsEyipTS+Q1d8F3FTjlAC5akzIwElnQekr8NoOCbnssLFiNU\nqKGfgomqJxCB26f9SU/jJ4cDB8bLASWnTtkxAV5eN/+sQDmD32vFu4qty44yyHLH\nwsKjlFEFAgMBAAECggEAWnA5PcvFs4Q2uJL1qOXcW74w9rbt/L2W3VBgK99Y/3po\n335nHQLuRD7YFueYi+/V7c3PNFQso64Ptw7Omd1oin25KA86rMkKsUazZTAN5idg\nO7pTzHhoPVOXa0lXkSCCCg9JcrYcGHSqa0aeoJhKkXd9EVFm3kNJ3kgKywFOFAIf\n3glN2DtejCFMQHkXwfeBdiaovyvZmUyw4qMseZrEaAJL8w1z/tmS0wWWW4TAsvE2\nFY0tdIkQ4xY1nHHr0DgbrnGHmedlAXK988gFzzGXCdQrlErB/EAGJ6y/W9ZTQeNm\nu7HBN0gVK+uiJRSO7QREi4yQTeAaSRfeOOGvTsIaHwKBgQDjEJoKxy8I3Ix2CtgK\ntLROZmVCzTv/9Xc1ks+d/KlBryYqdpcb4j+OrZOUlExYg3xkc0EAvI+BsT6LRTeG\nLKYBItDdfxU21Jqp2ZStV66qwKwtKuMELbDScnMycJfuXiuqrI1uwRNZN0aiFj9V\nnvh7GUC6GCRNgbDEx+Zz8d4nMwKBgQD46NzL7fuUcTOEESVa0O1XMBUviJjz2/f2\nS6mpGrl+DLNoZpEwhCjXZnEioSiBnIY5l+XqINBb6iCz8hJhFRkRybZO6oXSoByS\nTU1VmsUhkTre/K8gZ3roCuqlNbXGRtPwcHRoB3QwEn7Se2Mt55vAJzLE7YLmrrd2\nJrqceP1G5wKBgGmafwokOSvV5z2/LVtNT85msRGwggc5EohID2da6x0xaH63SPAe\n+ZBSCiFAF4HTJTdSoxjEmbmnxX7gYkJ04YAFwT5CrIsjtgrots0nyoR/t5QKLirz\nVRmLQShkVUT3ZzepqN2pzmXf32njDvGzTb1ysTfbooapmpzqm7Ow+bEhAoGBAIay\nNPHiehctXyxjvuwzTYy2B3DuoZ6tdbUB4vEQ9jpBE0E25/DyQ8u0sxqDTZE+K+C0\n04VsdnSW8VWPdOS2bxeSKabxALQnUu7VCDmABJeSqOIMqZSGixtQ9QOsWg7PO1fM\n8yFsjzKIf7rVbKllwYItdCrfQMwm1j8I3Elaq+0XAoGAAedHQnEROseYJEROOrLJ\nO/omh6Q0VSlE0qaM0RP5E9+ryRmTTmezy7flOT7L0r8phzHr0ZOHMRUJGLJTgmqm\nNE83SM1usTk92mVD9DgoI9Zgry/eywA8UUQEFlFr1ZA/8au4PkEs/QorlzzryGQx\ne3xkIv2wa+FNsRqiUmhbU9U=\n-----END PRIVATE KEY-----\n",
                "client_email"=> "laravel-app@name-382617.iam.gserviceaccount.com",
                "client_id"=> "115803926906968731869",
                "auth_uri"=> "https://accounts.google.com/o/oauth2/auth",
                "token_uri"=> "https://oauth2.googleapis.com/token",
                "auth_provider_x509_cert_url"=> "https://www.googleapis.com/oauth2/v1/certs",
                "client_x509_cert_url"=> "https://www.googleapis.com/robot/v1/metadata/x509/laravel-app%40name-382617.iam.gserviceaccount.com",
                "universe_domain"=> "googleapis.com"
            ],
            // 'project_id' => env('GOOGLE_CLOUD_PROJECT_ID'), // optional: is included in key file
            'bucket' => env('GOOGLE_CLOUD_STORAGE_BUCKET'),
            'path_prefix' => env('GOOGLE_CLOUD_STORAGE_PATH_PREFIX', ''), // optional: /default/path/to/apply/in/bucket
            'storage_api_uri' => env('GOOGLE_CLOUD_STORAGE_API_URI', ''), // see: Public URLs below
            'apiEndpoint' => env('GOOGLE_CLOUD_STORAGE_API_ENDPOINT', ''), // set storageClient apiEndpoint
            'visibility' => 'public', // optional: public|private
            'visibility_handler' => \League\Flysystem\GoogleCloudStorage\UniformBucketLevelAccessVisibility::class, // optional: set to \League\Flysystem\GoogleCloudStorage\UniformBucketLevelAccessVisibility::class to enable uniform bucket level access
            'metadata' => ['cacheControl'=> 'public,max-age=86400'], // optional: default metadata
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
