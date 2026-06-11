<?php

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Victorycodedev\MetaapiCloudPhpSdk\Exceptions\MetaApiException;

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
