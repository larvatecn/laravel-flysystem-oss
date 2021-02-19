<?php
/**
 * @copyright Copyright (c) 2018 Larva Information Technology Co., Ltd.
 * @link http://www.larvacent.com/
 * @license http://www.larvacent.com/license/
 */

namespace Larva\Flysystem\Aliyun;

use Illuminate\Support\ServiceProvider;
use Larva\Flysystem\Aliyun\Plugins\Cdn;
use Larva\Flysystem\Aliyun\Plugins\PutRemoteFile;
use Larva\Flysystem\Aliyun\Plugins\PutRemoteFileAs;
use League\Flysystem\Filesystem;
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

            $flysystem = new Filesystem(new OSSAdapter($client, $config), $config);
            $flysystem->addPlugin(new Cdn());
            $flysystem->addPlugin(new PutRemoteFile());
            $flysystem->addPlugin(new PutRemoteFileAs());

            return $flysystem;
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
