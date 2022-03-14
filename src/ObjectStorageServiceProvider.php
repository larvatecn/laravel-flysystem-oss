<?php
/**
 * This is NOT a freeware, use is subject to license terms
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 */

namespace Larva\Flysystem\Aliyun;

use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use Larva\Flysystem\Oss\OSSAdapter;

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
            $adapter = new OSSAdapter($config);
            return new FilesystemAdapter(new Filesystem($adapter), $adapter);
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
