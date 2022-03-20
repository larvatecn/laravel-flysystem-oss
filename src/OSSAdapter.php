<?php

namespace Larva\Flysystem\Aliyun;

use Carbon\Carbon;
use Illuminate\Contracts\Filesystem\Filesystem as FilesystemContract;
use OSS\Core\OssException;
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
     * The Aliyun Oss client.
     *
     * @var OssClient
     */
    protected OssClient $client;

    /**
     * Create a new OSSAdapter instance.
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
     * @throws OssException
     */
    public function url($path): string
    {
        // If an explicit base URL has been set on the disk configuration then we will use
        // it as the base URL instead of the default path. This allows the developer to
        // have full control over the base path for this filesystem's generated URLs.
        if (isset($this->config['url'])) {
            return $this->concatPathToUrl($this->config['url'], $this->prefixer->prefixPath($path));
        }
        $visibility = $this->getVisibility($path);
        if ($visibility == FilesystemContract::VISIBILITY_PRIVATE) {
            return $this->temporaryUrl($path, Carbon::now()->addMinutes(5), []);
        } else {
            $scheme = $this->config['ssl'] ? 'https://' : 'http://';
            return $this->concatPathToUrl($scheme . $this->config['bucket'] . '.' . $this->config['endpoint'], $this->prefixer->prefixPath($path));
        }
    }

    /**
     * Get a temporary URL for the file at the given path.
     *
     * @param string $path
     * @param \DateTimeInterface $expiration
     * @param array $options
     * @return string
     * @throws OssException
     */
    public function temporaryUrl($path, $expiration, array $options = []): string
    {
        $location = $this->prefixer->prefixPath($path);
        $timeout = $expiration->getTimestamp() - time();
        return $this->client->signUrl($this->config['bucket'], $location, $timeout, OssClient::OSS_HTTP_GET, $options);
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
