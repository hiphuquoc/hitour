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
                "private_key_id"=> "a33aa2f6cf5a81aff962670c0c53c427b0874e53",
                "private_key"=> "-----BEGIN PRIVATE KEY-----\nMIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQDP0B3qwOiq761y\n6w4BU4ZfD4t101LqUYXCcEWl1xA7Gpj6Gooz5gpS2W1vVhgWNrz/h7cgVNDW6BfZ\nFnlEPW+MkLT5XQwtA+mKzvNYiGDZcIigEk37CNvMXyWDUWmBA79xpzOyUEhn3YV3\nRRoS17r9Yp06xJ3pSIWM0TtM5ePfzuaMo8IZGs/tgpZNbhUBXpi6wjnWS2zDui6Z\nP/uO1NLJhApdy4bDiL8RZhfphY5M8DDXGjtQtTQkUaaa1ICJOAZk1Zv522YisctM\nJ6oIui2c3M+xqvwL8vlDZ/adJYJePAdHAZiRfTz5I/fP2GmXsyQHutO70pbFRwRD\ns8hUryvhAgMBAAECggEAN76wB8TvpMreUDFVdG2fYeidlGG3oDt2Eg5j4HSsWe5s\nksqDwCA5LLg+bts9YtgUIseZDAc0bjGcFBO/O9rvDVnT8gBPv9OI1j9lb55FvI4/\nWBEQ8gISU+RB/9Pa5UzIEgi0CWUXxyTZJIFY2S0db9MAMj+DzIwzpu6JZbtVQTeX\nQkuHjONAwmmt+tgaeuqPOx00lncqeXAd/BKz+wSqzftej/Ngey3bdMH9wJY9voR3\nspLXO11c0fWWdAWts31p+Rb/gFVSI3mXSmIIYyF6C23mOry/ZwEjUEmBbGq8XTYU\nFqSm0STAB1PXfVaumP41i5BucbH0ENM4iGvm57ybsQKBgQDr1joKiPwXZMpNzdzj\nnyxpz3nBjAGqZ4+6YhWQt5Mmm//3FEiRzQ/FHojnLaLuCTT1rPXbbfbiCCZ1b6BA\n2s20jN4acCeWOg+ANCi0j8X6L8s3bc60ffQ6Ko8ATAAOZ9hGCwoo4uAr/7biWkp7\nR2S02A8Oyvxb6MBp0Em1J5zkjwKBgQDhlIe/AzxJbkxJIaQarl6JeGliZMtn1XVt\ndLlMht1mtSTU+MBm+aRkGMMs2h22vL0QEzayRkfXcxkSKEou44zl0LrceQgPntZx\nesPpzlF/QHOaHqemgKYAQX4g7TC94USwkJcSokVxuR9IHiaZkUymYScq5u3Lc2YF\n3Ykwa/yAjwKBgQCKiFel0pvKniEcHP5REzJCoWZTJAvibl0GThF+5ebheiecmuSa\nCMvnra0bpzoa+3uJiOUO1YR9d+4nc7+9+Ql2snzRKOqIG2j5lSoIDqBr+2EzGSSf\n75DkDhXcRciOrb9kPZ3lMIggMGDci89OpTxp4rTNqr0cvBcy0PBLIo7NzwKBgQCI\nUW3G7HmeFl1bRE0bIoxWnsFwiGCzGrefsY3YxRc+XfEoEjqTAAFry9sGW7jhGSVX\ndYT0LqxzckaQRnCt7SzUJFMHsMxCREZqxTlnLRrqv1QcqrG9WLj4JpvF4hA2bs9H\ns9jXleBJXQmeVzoUS036rXMx5eOFsvLQUJP7AwbP2QKBgQDHsCbxQppwO9aXLlx6\nvEY4AFilKBywAA8GEpepYIv/hSZ6WZdPvS+IOceP1IeDjxybCA+7N6Z0ERWaLMmE\nr6RSsWZTsYKs/nwH7WMLa2kD6l9DN6PhjSwIkfDvsXU2IzFgb6CXwemL9pA5QdlD\nK0Pe0QEIOIW440WjX765WzfcrA==\n-----END PRIVATE KEY-----\n",
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
