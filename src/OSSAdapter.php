<?php

declare(strict_types=1);
/**
 * This is NOT a freeware, use is subject to license terms
 */

namespace Larva\Flysystem\Aliyun;

use OSS\OssClient;
use Illuminate\Filesystem\FilesystemAdapter;
use Larva\Flysystem\Oss\AliyunOSSAdapter;
use League\Flysystem\FilesystemOperator;

/**
 * OSS 适配器
 * @author Tongle Xu <xutongle@msn.com>
 */
class OSSAdapter extends FilesystemAdapter
{
    /**
     * The AWS S3 client.
     *
     * @var OssClient
     */
    protected OssClient $client;

    /**
     * Create a new AwsS3V3FilesystemAdapter instance.
     *
     * @param FilesystemOperator $driver
     * @param AliyunOSSAdapter $adapter
     * @param array $config
     * @param OssClient $client
     */
    public function __construct(FilesystemOperator $driver, AliyunOSSAdapter $adapter, array $config, OssClient $client)
    {
        parent::__construct($driver, $adapter, $config);
        $this->client = $client;
    }

    /**
     * Get the URL for the file at the given path.
     *
     * @param string $path
     * @return string
     *
     * @throws \RuntimeException
     */
    public function url($path): string
    {
        // If an explicit base URL has been set on the disk configuration then we will use
        // it as the base URL instead of the default path. This allows the developer to
        // have full control over the base path for this filesystem's generated URLs.
        if (isset($this->config['url'])) {
            return $this->concatPathToUrl($this->config['url'], $this->prefixer->prefixPath($path));
        }
        return $this->client->getObjectUrl($this->config['bucket'], $this->prefixer->prefixPath($path));
    }

    /**
     * Get a temporary URL for the file at the given path.
     *
     * @param string $path
     * @param \DateTimeInterface $expiration
     * @param array $options
     * @return string
     */
    public function temporaryUrl($path, $expiration, array $options = [])
    {
        $command = $this->client->getCommand('GetObject', array_merge([
            'Bucket' => $this->config['bucket'],
            'Key' => $this->prefixer->prefixPath($path),
        ], $options));

        $uri = $this->client->createPresignedRequest(
            $command, $expiration, $options
        )->getUri();

        // If an explicit base URL has been set on the disk configuration then we will use
        // it as the base URL instead of the default path. This allows the developer to
        // have full control over the base path for this filesystem's generated URLs.
        if (isset($this->config['temporary_url'])) {
            $uri = $this->replaceBaseUrl($uri, $this->config['temporary_url']);
        }

        return (string)$uri;
    }

    /**
     * Get the underlying OSS client.
     *
     * @return OssClient
     */
    public function getClient(): OssClient
    {
        return $this->client;
    }
}