<?php
/**
 * This is NOT a freeware, use is subject to license terms
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */

namespace Larva\Flysystem\Aliyun\Tests;

use Larva\Flysystem\Aliyun\OSSAdapter;
use Larva\Flysystem\Aliyun\Plugins\Cdn;
use League\Flysystem\Filesystem;
use OSS\OssClient;
use PHPUnit\Framework\TestCase;

class CDNSignatureTest extends TestCase
{
    public function Provider()
    {
        $config = [
            'access_id' => getenv('OSS_ACCESS_ID'),
            'access_key' => getenv('OSS_ACCESS_KEY'),
            'bucket' => getenv('OSS_BUCKET'),
            'endpoint' => getenv('OSS_ENDPOINT'),
            'url' => getenv('OSS_DOMAIN', 'cname url'),
            'prefix' => getenv('OSS_PREFIX', ''), // optional
            'security_token' => null,
            'proxy' => null,
            'timeout' => 3600,
            'ssl' => true,
            'cdn_key' => 'izDMqzld6U4AFQjg',
        ];

        $client = new OssClient($config['access_id'], $config['access_key'], $config['endpoint'],
            false,
            $config['security_token'] ?? null,
            $config['proxy'] ?? null);
        $client->setTimeout($config['timeout'] ?? 3600);
        $client->setUseSSL($config['ssl'] ?? false);

        $adapter = new OSSAdapter($client, $config);
        $filesystem = new Filesystem($adapter, $config);
        $filesystem->addPlugin(new Cdn());
        return [
            [$filesystem],
        ];
    }

    /**
     * @dataProvider Provider
     */
    public function testSignature(Filesystem $filesystem)
    {
        $this->assertSame(
            'http://www.test.com/1.mp4?auth_key=1584193991-111-0-7e933eb166d101d68f4af98adaa872d2',
            $filesystem->cdn()->signature('http://www.test.com/1.mp4', null, 1584193991, 111)
        );
    }

    /**
     * @dataProvider Provider
     */
    public function testSignatureA(Filesystem $filesystem)
    {
        $this->assertSame(
            'http://www.test.com/1.mp4?auth_key=1584193991-123456123123-0-3b2ddbfee5227ebc520a90fa6182df3c',
            $filesystem->cdn()->signatureA('http://www.test.com/1.mp4', null, 1584193991, '123456123123')
        );
    }

    /**
     * @dataProvider Provider
     */
    public function testSignatureB(Filesystem $filesystem)
    {
        date_default_timezone_set('UTC');

        $this->assertSame(
            'http://www.test.com/20200314135311/7ef7c5c28aff94862db1c5525649915c/1.mp4',
            $filesystem->cdn()->signatureB('http://www.test.com/1.mp4', null, 1584193991)
        );
    }

    /**
     * @dataProvider Provider
     */
    public function testSignatureC(Filesystem $filesystem)
    {
        $this->assertSame(
            'http://www.test.com/998883560007376ea1c3feea6fdba557/5e6ce1c7/1.mp4',
            $filesystem->cdn()->signatureC('http://www.test.com/1.mp4', null, 1584193991)
        );
    }
}
