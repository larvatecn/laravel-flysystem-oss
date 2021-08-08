# flysystem-aliyun-oss

This is a Flysystem adapter for the Aliyun OSS

[![Build Status](https://travis-ci.com/larvatecn/laravel-flysystem-oss.svg?branch=master)](https://travis-ci.com/larvatecn/laravel-flysystem-oss)

## Installation

```bash
composer require larva/laravel-flysystem-oss -vv
```

## for Laravel

This service provider must be registered.

```php
// config/app.php

'providers' => [
    '...',
    Larva\Flysystem\Aliyun\ObjectStorageServiceProvider::class,
];
```

edit the config file: config/filesystems.php

add config

```php
'oss' => [
    'driver'     => 'oss',
    'access_id' => env('OSS_ACCESS_ID', 'your id'),
    'access_key' => env('OSS_ACCESS_KEY', 'your key'),
    'bucket' => env('OSS_BUCKET', 'your bucket'),
    'endpoint' => env('OSS_ENDPOINT', 'your endpoint'),//不要用CName,经过测试，官方SDK实现不靠谱
    'url' => env('OSS_URL','cdn url'),//CNAME 写这里，可以是域名绑定或者CDN地址 如 https://www.bbb.com 末尾不要斜杠
    'prefix' => env('OSS_PREFIX', ''), // 这个文件路径前缀，如果上传的内容全部在子目录就填写，否则为空
    'security_token' => null,
    'proxy' => null,
    'timeout' => 3600,
    'ssl' => true
],
```

change default to oss

```php
    'default' => 'oss'
```

## Use

see [Laravel wiki](https://laravel.com/docs/5.6/filesystem)
