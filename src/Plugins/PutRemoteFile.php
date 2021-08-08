<?php
/**
 * This is NOT a freeware, use is subject to license terms
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */

namespace Larva\Flysystem\Aliyun\Plugins;

use League\Flysystem\Plugin\AbstractPlugin;
use League\Flysystem\Util\MimeType;
use Symfony\Component\HttpFoundation\File\MimeType\ExtensionGuesser;

/**
 * Class PutRemoteFile
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class PutRemoteFile extends AbstractPlugin
{
    /**
     * Get the method name.
     *
     * @return string
     */
    public function getMethod(): string
    {
        return 'putRemoteFile';
    }

    /**
     * @param       $path
     * @param       $remoteUrl
     * @param array $options
     *
     * @return string|false
     */
    public function handle($path, $remoteUrl, array $options = [])
    {
        //Get file from remote url
        $contents = @file_get_contents($remoteUrl);

        $filename = md5($contents);
        $extension = ExtensionGuesser::getInstance()->guess(MimeType::detectByContent($contents));
        $name = $filename . '.' . $extension;

        $path = trim($path . '/' . $name, '/');

        return $this->filesystem->put($path, $contents, $options) ? $path : false;
    }
}
