<?php

use GuzzleHttp\Psr7\Response;

it('supports MT4 manager account and configuration endpoints', function (): void {
    $history = [];
    $manager = managerWithHistory(array_fill(0, 19, new Response(200, [], '[]')), $history);
    $mt4 = $manager->mt4();

    $mt4->createAccount('profile/id', ['email' => 'user@example.com', 'group' => 'Demo', 'leverage' => 100]);
    $mt4->removeAccount('profile/id', 12345);
    $mt4->setAccountEnabled('profile/id', 12345, true);
    $mt4->groups('profile/id');
    $mt4->accountsByGroup('profile/id', 'Example\\Group');
    $mt4->accountByLogin('profile/id', 12345);
    $mt4->groupByName('profile/id', 'Example\\Group');
    $mt4->balanceOperation('profile/id', 12345, ['amount' => 100, 'type' => 'OP_BALANCE']);
    $mt4->changePassword('profile/id', 12345, 'secret', 'investor');
    $mt4->accountInformation('profile/id', 12345);
    $mt4->setChangePasswordPermission('profile/id', 12345, true);
    $mt4->changeUserGroup('profile/id', 12345, 'Live');
    $mt4->accountAuthenticationLogs('profile/id', 12345, ['from' => '2020-08-24 00:00:00.000']);
    $mt4->serverAuthenticationLogs('profile/id', ['to' => '2020-08-25 00:00:00.000']);
    $mt4->setAccountReadonly('profile/id', 12345, false);
    $mt4->checkPassword('profile/id', 12345, 'secret');
    $mt4->symbols('profile/id', ['included' => 'EUR.*']);
    $mt4->symbolsByGroup('profile/id', 1);
    $mt4->setAccountStatus('profile/id', 12345, 'ACTIVE');

    expect($history[0]['request'])->toHaveSentRequest('POST', '/users/current/mt4/provisioning-profiles/profile%2Fid/accounts');
    expect($history[1]['request'])->toHaveSentRequest('DELETE', '/users/current/mt4/provisioning-profiles/profile%2Fid/accounts/12345');
    expect($history[2]['request'])->toHaveSentRequest('PUT', '/users/current/mt4/provisioning-profiles/profile%2Fid/accounts/12345/enabled');
    expect(json_decode((string) $history[2]['request']->getBody(), true))->toBe(['enabled' => true]);
    expect($history[3]['request'])->toHaveSentRequest('GET', '/users/current/mt4/provisioning-profiles/profile%2Fid/groups');
    expect($history[4]['request'])->toHaveSentRequest('GET', '/users/current/mt4/provisioning-profiles/profile%2Fid/groups/Example%5CGroup/accounts');
    expect($history[5]['request'])->toHaveSentRequest('GET', '/users/current/mt4/provisioning-profiles/profile%2Fid/accounts/12345');
    expect($history[6]['request'])->toHaveSentRequest('GET', '/users/current/mt4/provisioning-profiles/profile%2Fid/groups/Example%5CGroup');
    expect($history[7]['request'])->toHaveSentRequest('POST', '/users/current/mt4/provisioning-profiles/profile%2Fid/accounts/12345/balance-transactions');
    expect($history[8]['request'])->toHaveSentRequest('PUT', '/users/current/mt4/provisioning-profiles/profile%2Fid/accounts/12345/password');
    expect($history[9]['request'])->toHaveSentRequest('GET', '/users/current/mt4/provisioning-profiles/profile%2Fid/accounts/12345/account-information');
    expect($history[10]['request'])->toHaveSentRequest('PUT', '/users/current/mt4/provisioning-profiles/profile%2Fid/accounts/12345/change-password-permission');
    expect($history[11]['request'])->toHaveSentRequest('PUT', '/users/current/mt4/provisioning-profiles/profile%2Fid/accounts/12345/group');
    expect($history[12]['request'])->toHaveSentRequest('GET', '/users/current/mt4/provisioning-profiles/profile%2Fid/accounts/12345/authentication-logs');
    expect($history[12]['request']->getUri()->getQuery())->toBe('from=2020-08-24%2000%3A00%3A00.000');
    expect($history[13]['request'])->toHaveSentRequest('GET', '/users/current/mt4/provisioning-profiles/profile%2Fid/authentication-logs');
    expect($history[14]['request'])->toHaveSentRequest('PUT', '/users/current/mt4/provisioning-profiles/profile%2Fid/accounts/12345/readonly');
    expect($history[15]['request'])->toHaveSentRequest('POST', '/users/current/mt4/provisioning-profiles/profile%2Fid/accounts/12345/checkPassword');
    expect($history[16]['request'])->toHaveSentRequest('GET', '/users/current/mt4/provisioning-profiles/profile%2Fid/symbols');
    expect($history[16]['request']->getUri()->getQuery())->toBe('included=EUR.%2A');
    expect($history[17]['request'])->toHaveSentRequest('GET', '/users/current/mt4/provisioning-profiles/profile%2Fid/symbolsGroup');
    expect($history[17]['request']->getUri()->getQuery())->toBe('group=1');
    expect($history[18]['request'])->toHaveSentRequest('PUT', '/users/current/mt4/provisioning-profiles/profile%2Fid/accounts/12345/status');
});

it('supports MT5 manager endpoints including trading permission', function (): void {
    $history = [];
    $manager = managerWithHistory([
        new Response(200, [], '[]'),
        new Response(200, [], '[]'),
    ], $history);

    $manager->mt5()->createAccount('profile-id', ['email' => 'user@example.com']);
    $manager->mt5()->setAccountTrading('profile-id', 12345, true);

    expect($history[0]['request'])->toHaveSentRequest('POST', '/users/current/mt5/provisioning-profiles/profile-id/accounts');
    expect($history[1]['request'])->toHaveSentRequest('PUT', '/users/current/mt5/provisioning-profiles/profile-id/accounts/12345/trading-enabled');
    expect(json_decode((string) $history[1]['request']->getBody(), true))->toBe(['trading' => true]);
});
