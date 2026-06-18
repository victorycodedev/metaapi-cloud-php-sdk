<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Victorycodedev\MetaapiCloudPhpSdk\Exceptions\MetaApiException;
use Victorycodedev\MetaapiCloudPhpSdk\Responses\ActionResponse;

it('decodes json responses', function (): void {
    $http = mockHttp([
        new Response(200, [], '{"id":"account-id","state":"DEPLOYED"}'),
    ]);

    expect($http->get('/users/current/accounts/account-id'))->toBe([
        'id'    => 'account-id',
        'state' => 'DEPLOYED',
    ]);
});

it('returns null for empty success responses', function (): void {
    $http = mockHttp([
        new Response(204),
    ]);

    expect($http->put('/users/current/accounts/account-id', ['name' => 'Updated']))->toBeNull();
});

it('normalizes boolean query parameters', function (): void {
    $history = [];
    $http = mockHttp([
        new Response(200, [], '[]'),
    ], $history);

    $http->get('/users/current/accounts', [
        'deploymentStatus' => ['deployed', 'failed'],
        'includeRemoved'   => false,
    ]);

    $query = $history[0]['request']->getUri()->getQuery();

    expect($query)->toContain('includeRemoved=false');
    expect($query)->toContain('deploymentStatus%5B0%5D=deployed');
});

it('applies default SDK headers when using a custom client', function (): void {
    $history = [];
    $http = mockHttp([
        new Response(200, [], '{"id":"account-id"}'),
    ], $history);

    $http->post('/users/current/accounts', ['name' => 'Demo'], ['transaction-id' => 'transaction-id']);

    $request = $history[0]['request'];

    expect($request->getHeaderLine('auth-token'))->toBe('test-token');
    expect($request->getHeaderLine('Accept'))->toBe('application/json');
    expect($request->getHeaderLine('Content-Type'))->toBe('application/json');
    expect($request->getHeaderLine('transaction-id'))->toBe('transaction-id');
});

it('does not force json content type onto uploads', function (): void {
    $history = [];
    $http = mockHttp([
        new Response(204),
    ], $history);
    $file = tempnam(sys_get_temp_dir(), 'metaapi-sdk-test');
    file_put_contents($file, 'server-data');

    try {
        $http->upload('/users/current/provisioning-profiles/profile-id/servers.dat', $file);
    } finally {
        unlink($file);
    }

    $request = $history[0]['request'];

    expect($request->getHeaderLine('auth-token'))->toBe('test-token');
    expect($request->getHeaderLine('Accept'))->toBe('application/json');
    expect($request->getHeaderLine('Content-Type'))->not->toBe('application/json');
});

it('can return action responses with status metadata', function (): void {
    $http = mockHttp([
        new Response(202, ['Retry-After' => '5'], '{"id":"account-id","state":"DRAFT"}'),
    ]);

    $response = $http->postAction('/users/current/accounts', ['name' => 'Demo']);

    expect($response)->toBeInstanceOf(ActionResponse::class);
    expect($response->statusCode())->toBe(202);
    expect($response->isAccepted())->toBeTrue();
    expect($response->isCreated())->toBeFalse();
    expect($response->shouldRetry())->toBeTrue();
    expect($response->retryAfter())->toBe('5');
    expect($response->id())->toBe('account-id');
    expect($response->state())->toBe('DRAFT');
    expect($response->body())->toBe(['id' => 'account-id', 'state' => 'DRAFT']);
});

it('throws structured MetaApi exceptions', function (): void {
    $http = mockHttp([
        new Response(400, [], '{"id":1,"error":"ValidationError","message":"Invalid account","details":{"code":"E_AUTH"}}'),
    ]);

    try {
        $http->post('/users/current/accounts', ['name' => 'Broken']);
    } catch (MetaApiException $exception) {
        expect($exception->statusCode())->toBe(400);
        expect($exception->errorId())->toBe(1);
        expect($exception->errorName())->toBe('ValidationError');
        expect($exception->details())->toBe(['code' => 'E_AUTH']);
        expect($exception->getMessage())->toBe('Invalid account');
        expect($exception->id())->toBe(1);
        expect($exception->error())->toBe('ValidationError');
        expect($exception->message())->toBe('Invalid account');
        expect($exception->toArray())->toBe([
            'id' => 1,
            'error' => 'ValidationError',
            'message' => 'Invalid account',
            'details' => ['code' => 'E_AUTH'],
        ]);

        return;
    }

    $this->fail('Expected MetaApiException to be thrown.');
});

it('exposes retry-after headers on rate limit errors', function (): void {
    $http = mockHttp([
        new Response(429, ['Retry-After' => '60'], '{"id":1,"error":"TooManyRequestsError","message":"Too many requests"}'),
    ]);

    expect(fn () => $http->get('/users/current/accounts'))
        ->toThrow(MetaApiException::class, 'Too many requests');
});

it('converts response errors from custom clients into MetaApiException', function (): void {
    $mock = new MockHandler([
        new Response(400, [], '{"id":2,"error":"ValidationError","message":"Invalid server"}'),
    ]);
    $client = new Client([
        'handler' => HandlerStack::create($mock),
        'http_errors' => true,
    ]);
    $http = new \Victorycodedev\MetaapiCloudPhpSdk\Http('test-token', 'https://example.test', $client);

    expect(fn () => $http->get('/users/current/accounts'))
        ->toThrow(MetaApiException::class, 'Invalid server');
});

it('wraps transport failures in MetaApiException', function (): void {
    $request = new Request('GET', 'https://example.test/users/current/accounts');
    $transportError = new ConnectException('Connection failed', $request);
    $mock = new MockHandler([$transportError]);
    $client = new Client(['handler' => HandlerStack::create($mock)]);
    $http = new \Victorycodedev\MetaapiCloudPhpSdk\Http('test-token', 'https://example.test', $client);

    try {
        $http->get('/users/current/accounts');
    } catch (MetaApiException $exception) {
        expect($exception->message())->toBe('Connection failed');
        expect($exception->statusCode())->toBe(0);
        expect($exception->getPrevious())->toBe($transportError);
        expect($exception->toArray())->toBe([
            'id' => null,
            'error' => null,
            'message' => 'Connection failed',
            'details' => null,
        ]);

        return;
    }

    $this->fail('Expected MetaApiException to be thrown.');
});
