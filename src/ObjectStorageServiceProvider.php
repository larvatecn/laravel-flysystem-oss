<?php
/**
 * This is NOT a freeware, use is subject to license terms
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 */

namespace Larva\Flysystem\Aliyun;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider;
use Larva\Flysystem\Aliyun\Plugins\Cdn;
use Larva\Flysystem\Aliyun\Plugins\PutRemoteFile;
use Larva\Flysystem\Aliyun\Plugins\PutRemoteFileAs;
use League\Flysystem\Filesystem;
use OSS\Core\OssException;
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
     * @throws BindingResolutionException|OssException
     */
    public function boot()
    {
        $this->app->make('filesystem')->extend('oss', function ($app, $config) {
            $client = new OssClient(
                $config['access_id'],
                $config['access_key'],
                $config['endpoint'],
                false,
                $config['security_token'] ?? null,
                $config['proxy'] ?? null
            );
            $client->setTimeout($config['timeout'] ?? 3600);
            $client->setConnectTimeout($config['connect_timeout'] ?? 10);
            $client->setUseSSL($config['ssl'] ?? false);

            return new Filesystem(new OSSAdapter($client, $config), $config);
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
