<?php

use GuzzleHttp\Psr7\Response;

it('supports risk management tracker endpoints', function (): void {
    $history = [];
    $risk = riskManagementWithHistory(array_fill(0, 10, new Response(200, [], '[]')), $history);
    $trackers = $risk->trackers();

    $trackers->create('account/id', ['name' => 'Daily drawdown']);
    $trackers->events(['accountId' => 'account/id', 'limit' => 100]);
    $trackers->eventStream(['previousSequenceNumber' => 5]);
    $trackers->statistics('account/id', 'tracker/id', ['realTime' => true]);
    $trackers->tracker('account/id', 'tracker/id');
    $trackers->trackerByName('account/id', 'Tracker 1');
    $trackers->trackers('account/id');
    $trackers->equityChart('account/id', ['startTime' => '2020-08-24 00:00:00.000']);
    $trackers->remove('account/id', 'tracker/id');
    $trackers->update('account/id', 'tracker/id', ['name' => 'Updated']);

    expect($history[0]['request'])->toHaveSentRequest('POST', '/users/current/accounts/account%2Fid/trackers');
    expect($history[0]['request']->getHeaderLine('api-version'))->toBe('1');
    expect($history[1]['request'])->toHaveSentRequest('GET', '/users/current/tracker-events/by-broker-time');
    expect($history[1]['request']->getUri()->getQuery())->toBe('accountId=account%2Fid&limit=100');
    expect($history[2]['request'])->toHaveSentRequest('GET', '/users/current/tracker-events/stream');
    expect($history[2]['request']->getHeaderLine('api-version'))->toBe('1');
    expect($history[2]['request']->getUri()->getQuery())->toBe('previousSequenceNumber=5');
    expect($history[3]['request'])->toHaveSentRequest('GET', '/users/current/accounts/account%2Fid/trackers/tracker%2Fid/statistics');
    expect($history[3]['request']->getUri()->getQuery())->toBe('realTime=true');
    expect($history[4]['request'])->toHaveSentRequest('GET', '/users/current/accounts/account%2Fid/trackers/tracker%2Fid');
    expect($history[5]['request'])->toHaveSentRequest('GET', '/users/current/accounts/account%2Fid/trackers/name/Tracker%201');
    expect($history[6]['request'])->toHaveSentRequest('GET', '/users/current/accounts/account%2Fid/trackers');
    expect($history[7]['request'])->toHaveSentRequest('GET', '/users/current/accounts/account%2Fid/equity-chart');
    expect($history[8]['request'])->toHaveSentRequest('DELETE', '/users/current/accounts/account%2Fid/trackers/tracker%2Fid');
    expect($history[9]['request'])->toHaveSentRequest('PUT', '/users/current/accounts/account%2Fid/trackers/tracker%2Fid');
});
