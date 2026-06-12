<?php

use GuzzleHttp\Psr7\Response;

it('reads provisioning profiles with filters', function (): void {
    $history = [];
    $metaapi = metaApiClientWithHistory([new Response(200, [], '[]')], $history);

    $metaapi->provisioningProfiles()->provisioningProfiles([
        'version' => 5,
        'status'  => 'new',
        'type'    => 'mtTerminal',
    ], apiVersion: 2);

    $request = $history[0]['request'];

    expect($request)->toHaveSentRequest('GET', '/users/current/provisioning-profiles');
    expect($request->getHeaderLine('api-version'))->toBe('2');
    expect($request->getUri()->getQuery())->toContain('version=5');
    expect($request->getUri()->getQuery())->toContain('status=new');
});

it('creates provisioning profiles', function (): void {
    $history = [];
    $metaapi = metaApiClientWithHistory([new Response(201, [], '{"id":"profile-id"}')], $history);

    $response = $metaapi->provisioningProfiles()->createProvisioningProfile([
        'name'                     => 'ICMarkets',
        'version'                  => 5,
        'brokerTimezone'           => 'EET',
        'brokerDSTSwitchTimezone'  => 'EET',
    ]);

    expect($response)->toBe(['id' => 'profile-id']);
    expect($history[0]['request'])->toHaveSentRequest('POST', '/users/current/provisioning-profiles');
});

it('deletes provisioning profiles', function (): void {
    $history = [];
    $metaapi = metaApiClientWithHistory([new Response(204)], $history);

    expect($metaapi->provisioningProfiles()->deleteProvisioningProfile('profile-id'))->toBeNull();
    expect($history[0]['request'])->toHaveSentRequest('DELETE', '/users/current/provisioning-profiles/profile-id');
});
