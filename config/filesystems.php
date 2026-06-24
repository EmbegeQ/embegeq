<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application for file storage.
    |
    */

    'default' => $_ENV['FILESYSTEM_DISK'] ?? 'local',

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Below you may configure as many filesystem disks as necessary, and you
    | may even configure multiple disks for the same driver. Examples for
    | most supported storage drivers are configured here for reference.
    |
    | Supported drivers: "local", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => __DIR__ . '/../storage/app/private',
            'throw' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => __DIR__ . '/../storage/app/public',
            'url' => rtrim($_ENV['APP_URL'] ?? 'http://localhost', '/') . '/storage',
            'visibility' => 'public',
            'throw' => false,
        ],

        /*
        |--------------------------------------------------------------------------
        | S3-Compatible Object Storage Template
        |--------------------------------------------------------------------------
        |
        | This disk template supports any S3-compatible service, including:
        | - Amazon S3
        | - Cloudflare R2
        | - Backblaze B2
        | - Google Cloud Storage (GCS via S3-API)
        | - Wasabi
        | - Nevacloud
        | - Akamai Object Storage
        | - Azure Blob Storage (via S3 gateway)
        |
        | To use this driver, make sure to install: composer require league/flysystem-aws-s3-v3
        |
        */
        's3' => [
            'driver' => 's3',
            'key' => $_ENV['AWS_ACCESS_KEY_ID'] ?? '',
            'secret' => $_ENV['AWS_SECRET_ACCESS_KEY'] ?? '',
            'region' => $_ENV['AWS_DEFAULT_REGION'] ?? 'us-east-1',
            'bucket' => $_ENV['AWS_BUCKET'] ?? '',
            'url' => $_ENV['AWS_URL'] ?? null,
            'endpoint' => $_ENV['AWS_ENDPOINT'] ?? null,
            'use_path_style_endpoint' => (bool) ($_ENV['AWS_USE_PATH_STYLE_ENDPOINT'] ?? false),
            'throw' => false,
        ],

    ],

];
