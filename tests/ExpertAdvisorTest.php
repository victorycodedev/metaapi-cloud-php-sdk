<?php

use GuzzleHttp\Psr7\Response;

it('lists expert advisors', function (): void {
    $history = [];
    $metaapi = metaApiClientWithHistory([
        new Response(200, [], '[{"expertId":"test","period":"1H","symbol":"EURUSD","fileUploaded":false}]'),
    ], $history);

    $result = $metaapi->expertAdvisors()->expertAdvisors('account-id');

    expect($result)->toBe([['expertId' => 'test', 'period' => '1H', 'symbol' => 'EURUSD', 'fileUploaded' => false]]);
    expect($history[0]['request'])->toHaveSentRequest('GET', '/users/current/accounts/account-id/expert-advisors');
});

it('reads a single expert advisor', function (): void {
    $history = [];
    $metaapi = metaApiClientWithHistory([
        new Response(200, [], '{"expertId":"test","period":"1H","symbol":"EURUSD","fileUploaded":true}'),
    ], $history);

    $result = $metaapi->expertAdvisors()->expertAdvisor('account-id', 'test');

    expect($result)->toBe(['expertId' => 'test', 'period' => '1H', 'symbol' => 'EURUSD', 'fileUploaded' => true]);
    expect($history[0]['request'])->toHaveSentRequest('GET', '/users/current/accounts/account-id/expert-advisors/test');
});

it('updates an expert advisor', function (): void {
    $history = [];
    $metaapi = metaApiClientWithHistory([new Response(204)], $history);

    $metaapi->expertAdvisors()->updateExpertAdvisor('account-id', 'test', [
        'symbol' => 'EURUSD',
        'period' => '1H',
        'preset' => 'base64-encoded-preset',
    ]);

    expect($history[0]['request'])->toHaveSentRequest('PUT', '/users/current/accounts/account-id/expert-advisors/test');
});

it('uploads an expert advisor file', function (): void {
    $filePath = sys_get_temp_dir() . '/test-expert.ex5';
    file_put_contents($filePath, 'fake-expert-content');

    $history = [];
    $metaapi = metaApiClientWithHistory([new Response(204)], $history);

    $metaapi->expertAdvisors()->uploadExpertAdvisorFile('account-id', 'test', $filePath);

    unlink($filePath);

    expect($history[0]['request'])->toHaveSentRequest('PUT', '/users/current/accounts/account-id/expert-advisors/test/file');
});

it('deletes an expert advisor', function (): void {
    $history = [];
    $metaapi = metaApiClientWithHistory([new Response(204)], $history);

    expect($metaapi->expertAdvisors()->deleteExpertAdvisor('account-id', 'test'))->toBeNull();
    expect($history[0]['request'])->toHaveSentRequest('DELETE', '/users/current/accounts/account-id/expert-advisors/test');
});
