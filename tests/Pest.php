<?php

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use PHPUnit\Framework\Assert;
use Victorycodedev\MetaapiCloudPhpSdk\AccountApi;
use Victorycodedev\MetaapiCloudPhpSdk\Http;
use Victorycodedev\MetaapiCloudPhpSdk\MetaApiClient;

expect()->extend('toHaveSentRequest', function (string $method, string $path): void {
    $request = $this->value;

    Assert::assertSame($method, $request->getMethod());
    Assert::assertSame($path, $request->getUri()->getPath());
});

function mockHttp(array $responses, array &$history = []): Http
{
    $mock = new MockHandler($responses);
    $stack = HandlerStack::create($mock);
    $stack->push(Middleware::history($history));

    return new Http('test-token', 'https://example.test', new Client([
        'handler'     => $stack,
        'http_errors' => false,
    ]));
}

function accountApiWithHistory(array $responses, array &$history = []): AccountApi
{
    $mock = new MockHandler($responses);
    $stack = HandlerStack::create($mock);
    $stack->push(Middleware::history($history));

    $client = new Client([
        'handler'     => $stack,
        'http_errors' => false,
    ]);

    return new AccountApi('test-token', 'https://mt-provisioning-api-v1.agiliumtrade.agiliumtrade.ai', $client);
}

function metaApiClientWithHistory(array $responses, array &$history = []): MetaApiClient
{
    $mock = new MockHandler($responses);
    $stack = HandlerStack::create($mock);
    $stack->push(Middleware::history($history));

    $client = new Client([
        'handler'     => $stack,
        'http_errors' => false,
    ]);

    return new MetaApiClient('test-token', $client);
}
