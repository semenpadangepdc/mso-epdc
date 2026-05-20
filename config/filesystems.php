<?php

/*
|--------------------------------------------------------------------------
| Parse LARAVEL_CLOUD_DISK_CONFIG
|--------------------------------------------------------------------------
|
| Laravel Cloud injects disk credentials as a JSON array in the env var
| LARAVEL_CLOUD_DISK_CONFIG. We decode it here and build an s3-compatible
| disk entry for each object, then merge into the disks array below.
|
*/

$cloudDisks = [];

if ($cloudDiskConfig = env('LARAVEL_CLOUD_DISK_CONFIG')) {
    $configs = json_decode($cloudDiskConfig, true) ?? [];

    foreach ($configs as $config) {
        $diskName = $config['disk'] ?? null;

        if (!$diskName) {
            continue;
        }

        $cloudDisks[$diskName] = [
            'driver'                  => 's3',
            'key'                     => $config['access_key_id'] ?? '',
            'secret'                  => $config['access_key_secret'] ?? '',
            'region'                  => $config['default_region'] ?? 'auto',
            'bucket'                  => $config['bucket'] ?? '',
            'url'                     => $config['url'] ?? null,
            'endpoint'                => $config['endpoint'] ?? null,
            'use_path_style_endpoint' => $config['use_path_style_endpoint'] ?? false,
            'visibility'              => 'public',
            'throw'                   => false,
        ];
    }
}

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    */

    'disks' => array_merge([

        'local' => [
            'driver' => 'local',
            'root'   => storage_path('app'),
            'throw'  => false,
        ],

        'public' => [
            'driver'     => 'local',
            'root'       => storage_path('app/public'),
            'url'        => env('APP_URL') . '/storage',
            'visibility' => 'public',
            'throw'      => false,
        ],

        's3' => [
            'driver'                  => 's3',
            'key'                     => env('AWS_ACCESS_KEY_ID'),
            'secret'                  => env('AWS_SECRET_ACCESS_KEY'),
            'region'                  => env('AWS_DEFAULT_REGION'),
            'bucket'                  => env('AWS_BUCKET'),
            'url'                     => env('AWS_URL'),
            'endpoint'                => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw'                   => false,
        ],

    ], $cloudDisks),

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];