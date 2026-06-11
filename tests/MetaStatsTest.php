<?php

use GuzzleHttp\Psr7\Response;

it('supports metastats metrics endpoints', function (): void {
    $history = [];
    $stats = metaStatsWithHistory([
        new Response(200, [], '{"metrics":[]}'),
        new Response(200, [], '{"trades":[]}'),
        new Response(200, [], '{"openTrades":[]}'),
        new Response(204),
    ], $history);

    $stats->metrics('account-id', true);
    $stats->historicalTrades('account-id', '2020-09-08 22:21:36.000', '2020-09-09 22:21:36.000', [
        'limit' => 100,
        'offset' => 10,
        'updateHistory' => true,
    ]);
    $stats->openTrades('account-id');
    $stats->resetMetrics('account-id');

    expect($history[0]['request'])->toHaveSentRequest('GET', '/users/current/accounts/account-id/metrics');
    expect($history[0]['request']->getUri()->getQuery())->toBe('includeOpenPositions=true');
    expect($history[1]['request'])->toHaveSentRequest('GET', '/users/current/accounts/account-id/historical-trades/2020-09-08%2022%3A21%3A36.000/2020-09-09%2022%3A21%3A36.000');
    expect($history[1]['request']->getUri()->getQuery())->toContain('updateHistory=true');
    expect($history[2]['request'])->toHaveSentRequest('GET', '/users/current/accounts/account-id/open-trades');
    expect($history[3]['request'])->toHaveSentRequest('DELETE', '/users/current/accounts/account-id');
});

it('exposes the MetaStats metrics resource', function (): void {
    $stats = metaStatsWithHistory([new Response(200, [], '{"openTrades":[]}')]);

    expect($stats->metricResource()->openTrades('account-id'))->toBe(['openTrades' => []]);
});
