<?php
/**
 * This is NOT a freeware, use is subject to license terms
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */

namespace Larva\Flysystem\Aliyun\Plugins;

use League\Flysystem\Plugin\AbstractPlugin;

class PutRemoteFileAs extends AbstractPlugin
{
    /**
     * Get the method name.
     *
     * @return string
     */
    public function getMethod(): string
    {
        return 'putRemoteFileAs';
    }

    /**
     * @param       $path
     * @param       $remoteUrl
     * @param       $name
     * @param array $options
     *
     * @return string|false
     */
    public function handle($path, $remoteUrl, $name, array $options = [])
    {
        //Get file from remote url
        $contents = @file_get_contents($remoteUrl);

        $path = trim($path . '/' . $name, '/');

        return $this->filesystem->put($path, $contents, $options) ? $path : false;
    }
}
