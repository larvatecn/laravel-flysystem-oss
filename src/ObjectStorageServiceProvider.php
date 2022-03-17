<?php
/**
 * This is NOT a freeware, use is subject to license terms
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 */

namespace Larva\Flysystem\Aliyun;

use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Larva\Flysystem\Oss\AliyunOSSAdapter;
use Larva\Flysystem\Oss\PortableVisibilityConverter;
use League\Flysystem\Filesystem as Flysystem;
use League\Flysystem\Visibility;
use OSS\OssClient;

/**
 * 阿里云OSS服务提供
 */
class ObjectStorageServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function boot()
    {
        $this->app->make('filesystem')->extend('oss', function ($app, $config) {
            $root = (string)($config['root'] ?? '');
            $config['directory_separator'] = '/';
            $visibility = new PortableVisibilityConverter($config['visibility'] ?? Visibility::PUBLIC);
            $client = new OSSClient($config['access_id'], $config['access_key'], $config['endpoint'], false, $config['security_token'] ?? null, $config['proxy'] ?? null);
            $adapter = new AliyunOSSAdapter($client, $config['bucket'], $root, $visibility, null, $config['options'] ?? []);

            return new OSSAdapter(
                new Flysystem($adapter, Arr::only($config, [
                    'directory_visibility',
                    'disable_asserts',
                    'temporary_url',
                    'url',
                    'visibility',
                ])), $adapter, $config, $client
            );
        });
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
