<?php

use GuzzleHttp\Psr7\Response;
use Victorycodedev\MetaapiCloudPhpSdk\Responses\ActionResponse;

it('creates mt4 demo account with transaction id header', function (): void {
    $history = [];
    $metaapi = metaApiClientWithHistory([
        new Response(201, [], '{"login":"86053193","password":"2y8kpft","investorPassword":"dc56esco","serverName":"Example-Server"}'),
    ], $history);

    $response = $metaapi->demoAccounts()->createMT4DemoAccount('profile-id', [
        'accountType' => 'type',
        'balance'     => 1000,
        'email'       => 'user@example.com',
        'leverage'    => 10,
        'name'        => 'Test User',
        'phone'       => '+12345678901',
        'serverName'  => 'Example-Server',
    ], 'transaction-id-1234567890123456');

    expect($response)->toBeInstanceOf(ActionResponse::class);
    expect($response->isCreated())->toBeTrue();
    expect($response->body())->toBe([
        'login'           => '86053193',
        'password'        => '2y8kpft',
        'investorPassword' => 'dc56esco',
        'serverName'      => 'Example-Server',
    ]);
    expect($history[0]['request'])->toHaveSentRequest('POST', '/users/current/provisioning-profiles/profile-id/mt4-demo-accounts');
    expect($history[0]['request']->getHeaderLine('transaction-id'))->toBe('transaction-id-1234567890123456');
});

it('creates mt5 demo account with transaction id header', function (): void {
    $history = [];
    $metaapi = metaApiClientWithHistory([
        new Response(201, [], '{"login":"86053193","password":"2y8kpft","investorPassword":"dc56esco","serverName":"Example-Server"}'),
    ], $history);

    $response = $metaapi->demoAccounts()->createMT5DemoAccount('profile-id', [
        'accountType' => 'type',
        'balance'     => 1000,
        'email'       => 'user@example.com',
        'leverage'    => 10,
        'name'        => 'Test User',
        'phone'       => '+12345678901',
        'serverName'  => 'Example-Server',
    ], 'transaction-id-1234567890123456');

    expect($response)->toBeInstanceOf(ActionResponse::class);
    expect($response->isCreated())->toBeTrue();
    expect($history[0]['request'])->toHaveSentRequest('POST', '/users/current/provisioning-profiles/profile-id/mt5-demo-accounts');
    expect($history[0]['request']->getHeaderLine('transaction-id'))->toBe('transaction-id-1234567890123456');
});

it('exposes accepted demo account creation retry metadata', function (): void {
    $metaapi = metaApiClientWithHistory([
        new Response(202, ['Retry-After' => '10'], '{"id":"request-id","state":"DRAFT"}'),
    ]);

    $response = $metaapi->demoAccounts()->createMT4DemoAccount('profile-id', [], 'transaction-id');

    expect($response)->toBeInstanceOf(ActionResponse::class);
    expect($response->isAccepted())->toBeTrue();
    expect($response->shouldRetry())->toBeTrue();
    expect($response->retryAfter())->toBe('10');
    expect($response->id())->toBe('request-id');
    expect($response->state())->toBe('DRAFT');
});
