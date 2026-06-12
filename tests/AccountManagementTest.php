<?php

use GuzzleHttp\Psr7\Response;

it('reads accounts with filters and api version header', function (): void {
    $history = [];
    $metaapi = metaApiClientWithHistory([new Response(200, [], '{"count":0,"items":[]}')], $history);

    $metaapi->accounts()->accounts([
        'deploymentStatus' => ['deployed'],
        'limit'            => 50,
    ], apiVersion: 2);

    $request = $history[0]['request'];

    expect($request)->toHaveSentRequest('GET', '/users/current/accounts');
    expect($request->getHeaderLine('api-version'))->toBe('2');
    expect($request->getUri()->getQuery())->toContain('deploymentStatus%5B0%5D=deployed');
    expect($request->getUri()->getQuery())->toContain('limit=50');
});

it('creates accounts with transaction id headers', function (): void {
    $history = [];
    $metaapi = metaApiClientWithHistory([new Response(201, [], '{"id":"account-id","state":"DEPLOYED"}')], $history);

    $response = $metaapi->accounts()->create(['name' => 'Demo', 'server' => 'ICMarketsSC-Demo'], '12345678901234567890123456789012');

    expect($response)->toBe(['id' => 'account-id', 'state' => 'DEPLOYED']);
    expect($history[0]['request'])->toHaveSentRequest('POST', '/users/current/accounts');
    expect($history[0]['request']->getHeaderLine('transaction-id'))->toBe('12345678901234567890123456789012');
});

it('deletes accounts using the account endpoint', function (): void {
    $history = [];
    $metaapi = metaApiClientWithHistory([new Response(204)], $history);

    expect($metaapi->accounts()->delete('account-id', true))->toBeNull();
    expect($history[0]['request'])->toHaveSentRequest('DELETE', '/users/current/accounts/account-id');
    expect($history[0]['request']->getUri()->getQuery())->toBe('executeForAllReplicas=true');
});

it('enables account features', function (): void {
    $history = [];
    $metaapi = metaApiClientWithHistory([new Response(204)], $history);

    $metaapi->accounts()->enableFeatures('account-id', [
        'metastatsApiEnabled' => true,
        'reliabilityIncreased' => true,
    ]);

    expect($history[0]['request'])->toHaveSentRequest('POST', '/users/current/accounts/account-id/enable-account-features');
});

it('creates account replicas', function (): void {
    $history = [];
    $metaapi = metaApiClientWithHistory([new Response(201, [], '{"id":"replica-id","state":"DEPLOYED"}')], $history);

    $metaapi->accountReplicas()->createReplica('account-id', ['magic' => 123456, 'region' => 'london'], 'transaction-id');

    expect($history[0]['request'])->toHaveSentRequest('POST', '/users/current/accounts/account-id/replicas');
    expect($history[0]['request']->getHeaderLine('transaction-id'))->toBe('transaction-id');
});
