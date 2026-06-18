<?php

use GuzzleHttp\Psr7\Response;

it('gets user quotas', function (): void {
    $history = [];
    $metaapi = metaApiClientWithHistory([
        new Response(200, [], '[{"region":"vint-hill","maxAccounts":{"quota":100}}]'),
    ], $history);

    $result = $metaapi->quotas()->quotas();

    expect($result)->toBe([['region' => 'vint-hill', 'maxAccounts' => ['quota' => 100]]]);
    expect($history[0]['request'])->toHaveSentRequest('GET', '/users/current/quotas');
});

it('gets quota update requests', function (): void {
    $history = [];
    $metaapi = metaApiClientWithHistory([
        new Response(200, [], '[{"region":"vint-hill","maxAccounts":150}]'),
    ], $history);

    $result = $metaapi->quotas()->quotaUpdateRequests();

    expect($result)->toBe([['region' => 'vint-hill', 'maxAccounts' => 150]]);
    expect($history[0]['request'])->toHaveSentRequest('GET', '/users/current/quota-update-requests');
});

it('requests a region quota update', function (): void {
    $history = [];
    $metaapi = metaApiClientWithHistory([new Response(204)], $history);

    $metaapi->quotas()->requestRegionQuotaUpdate('vint-hill', [
        'maxAccounts' => 150,
        'maxDeployedG1Accounts' => 25,
        'justification' => 'quota update request justification message',
    ]);

    expect($history[0]['request'])->toHaveSentRequest('PATCH', '/users/current/regions/vint-hill/quotas');
});
