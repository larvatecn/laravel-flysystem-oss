# Laravel-flysystem-oss

<p align="center">
    <a href="https://packagist.org/packages/larva/laravel-flysystem-oss"><img src="https://poser.pugx.org/larva/laravel-flysystem-oss/v/stable" alt="Stable Version"></a>
    <a href="https://packagist.org/packages/larva/laravel-flysystem-oss"><img src="https://poser.pugx.org/larva/laravel-flysystem-oss/downloads" alt="Total Downloads"></a>
    <a href="https://packagist.org/packages/larva/laravel-flysystem-oss"><img src="https://poser.pugx.org/larva/laravel-flysystem-oss/license" alt="License"></a>
</p>

适用于 Laravel 的阿里云 OSS 适配器，完整支持阿里云 OSS 所有方法和操作。

## 安装

```bash
composer require larva/laravel-flysystem-oss -vv
```

修改配置文件: `config/filesystems.php`

添加一个磁盘配置

```php
'oss' => [
    'driver'     => 'oss',
    'access_id' => env('OSS_ACCESS_ID', 'your id'),
    'access_key' => env('OSS_ACCESS_KEY', 'your key'),
    'bucket' => env('OSS_BUCKET', 'your bucket'),
    'endpoint' => env('OSS_ENDPOINT', 'your endpoint'),//不要用CName,经过测试，官方SDK实现不靠谱
    'url' => env('OSS_URL','cdn url'),//CNAME 写这里，可以是域名绑定或者CDN地址 如 https://www.bbb.com 末尾不要斜杠
    'root' => env('OSS_ROOT', ''), // 这个文件路径前缀，如果上传的内容全部在子目录就填写，否则为空
    'security_token' => null,
    'proxy' => null,
    'timeout' => 3600,
    'ssl' => true
],
```

修改默认存储驱动

```php
    'default' => 'oss'
```

## 使用

参见 [Laravel wiki](https://laravel.com/docs/9.x/filesystem)
