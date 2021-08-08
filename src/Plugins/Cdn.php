<?php
/**
 * This is NOT a freeware, use is subject to license terms
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */

namespace Larva\Flysystem\Aliyun\Plugins;

use League\Flysystem\Plugin\AbstractPlugin;

/**
 * Class Cdn
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Cdn extends AbstractPlugin
{
    /**
     * Get the method name.
     *
     * @return string
     */
    public function getMethod(): string
    {
        return 'cdn';
    }

    /**
     * @return $this
     */
    public function handle()
    {
        return $this;
    }

    /**
     * @param string $url
     * @param string $key
     * @param int $timestamp
     * @param string $random
     *
     * @return string
     */
    public function signature($url, $key = null, $timestamp = null, $random = null): string
    {
        return $this->signatureA($url, $key, $timestamp, $random);
    }

    /**
     * @param $url
     * @param null $key
     * @param null $timestamp
     * @param null $random
     * @param string $signName
     * @return string
     */
    public function signatureA($url, $key = null, $timestamp = null, $random = null, $signName = 'auth_key'): string
    {
        $key = $key ?: $this->filesystem->getConfig()->get('cdn_key');
        $timestamp = $timestamp ?: time();
        $random = $random ?: uniqid();
        $parsed = parse_url($url);
        $hash = md5(sprintf('%s-%s-%s-%s-%s', $parsed['path'], $timestamp, $random, 0, $key));
        $signature = sprintf('%s-%s-%s-%s', $timestamp, $random, 0, $hash);
        $query = http_build_query([$signName => $signature]);
        $separator = empty($parsed['query']) ? '?' : '&';
        return $url . $separator . $query;
    }

    /**
     * @param string $url
     * @param string $key
     * @param int $timestamp
     *
     * @return string
     */
    public function signatureB($url, $key = null, $timestamp = null): string
    {
        $key = $key ?: $this->filesystem->getConfig()->get('cdn_key');
        $timestamp = date('YmdHis', $timestamp ?: time());
        $parsed = parse_url($url);
        $hash = md5($key . $timestamp . $parsed['path']);
        return sprintf(
            '%s://%s/%s/%s%s',
            $parsed['scheme'], $parsed['host'], $timestamp, $hash, $parsed['path']
        );
    }

    /**
     * @param string $url
     * @param string $key
     * @param int $timestamp
     *
     * @return string
     */
    public function signatureC($url, $key = null, $timestamp = null): string
    {
        $key = $key ?: $this->filesystem->getConfig()->get('cdn_key');
        $timestamp = dechex($timestamp ?: time());
        $parsed = parse_url($url);
        $hash = md5($key . $parsed['path'] . $timestamp);

        return sprintf(
            '%s://%s/%s/%s%s',
            $parsed['scheme'], $parsed['host'], $hash, $timestamp, $parsed['path']
        );
    }
}
