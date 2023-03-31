<?php

namespace Victorycodedev\MetaapiCloudPhpSdk\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use PHPUnit\Framework\TestCase;
use Victorycodedev\MetaapiCloudPhpSdk\MetaApiCloud;

class MetaApiTest extends TestCase
{

    public function testMetaApiObjectCanBeInstantiated()
    {
        // Create a mock.
        $mock = new MockHandler();

        $handlerStack = HandlerStack::create($mock);

        new Client(['handler' => $handlerStack]);

        $metaapi = new MetaApiCloud('api-key');
        $this->assertInstanceOf(MetaApiCloud::class, $metaapi);
    }
}
