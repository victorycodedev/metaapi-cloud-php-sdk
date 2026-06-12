<?php

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use PHPUnit\Framework\Assert;
use Victorycodedev\MetaapiCloudPhpSdk\CopyFactory;
use Victorycodedev\MetaapiCloudPhpSdk\Http;
use Victorycodedev\MetaapiCloudPhpSdk\MetaApiClient;
use Victorycodedev\MetaapiCloudPhpSdk\MetaStats;
use Victorycodedev\MetaapiCloudPhpSdk\TerminalApi;

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

function copyFactoryWithHistory(array $responses, array &$history = []): CopyFactory
{
    $mock = new MockHandler($responses);
    $stack = HandlerStack::create($mock);
    $stack->push(Middleware::history($history));

    $client = new Client([
        'handler'     => $stack,
        'http_errors' => false,
    ]);

    return new CopyFactory('test-token', 'https://copyfactory-api-v1.new-york.agiliumtrade.ai', $client);
}

function metaStatsWithHistory(array $responses, array &$history = []): MetaStats
{
    $mock = new MockHandler($responses);
    $stack = HandlerStack::create($mock);
    $stack->push(Middleware::history($history));

    $client = new Client([
        'handler'     => $stack,
        'http_errors' => false,
    ]);

    return new MetaStats('test-token', 'https://metastats-api-v1.new-york.agiliumtrade.ai', $client);
}

function terminalWithHistory(array $responses, array &$history = []): TerminalApi
{
    $mock = new MockHandler($responses);
    $stack = HandlerStack::create($mock);
    $stack->push(Middleware::history($history));

    $client = new Client([
        'handler'     => $stack,
        'http_errors' => false,
    ]);

    return new TerminalApi(
        'test-token',
        'https://mt-client-api-v1.new-york.agiliumtrade.ai',
        'https://mt-market-data-client-api-v1.new-york.agiliumtrade.ai',
        $client
    );
}
