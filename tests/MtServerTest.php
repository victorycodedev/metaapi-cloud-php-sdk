<?php

use GuzzleHttp\Psr7\Response;

it('searches known trading servers by version and query', function (): void {
    $history = [];
    $metaapi = metaApiClientWithHistory([
        new Response(200, [], '{"Raw Trading Ltd":["ICMarketsSC-Demo","ICMarketsSC-MT5"]}'),
    ], $history);

    $result = $metaapi->mtServers()->knownTradingServers(5, 'icmarketssc');

    expect($result)->toBe(['Raw Trading Ltd' => ['ICMarketsSC-Demo', 'ICMarketsSC-MT5']]);
    expect($history[0]['request'])->toHaveSentRequest('GET', '/known-mt-servers/5/search');
    expect($history[0]['request']->getUri()->getQuery())->toBe('query=icmarketssc');
});
