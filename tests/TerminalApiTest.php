<?php

use GuzzleHttp\Psr7\Response;

it('reads trading terminal state', function (): void {
    $history = [];
    $terminal = terminalWithHistory([
        new Response(200, [], '{"broker":"MetaQuotes"}'),
        new Response(200, [], '[]'),
        new Response(200, [], '{"id":"position-id"}'),
        new Response(200, [], '[]'),
        new Response(200, [], '{"id":"order-id"}'),
        new Response(200, [], '{"time":"2020-09-08T22:21:36.000Z"}'),
    ], $history);

    $terminal->state()->accountInformation('account-id', true);
    $terminal->state()->positions('account-id');
    $terminal->state()->position('account-id', 'position/id');
    $terminal->state()->orders('account-id');
    $terminal->state()->order('account-id', 'order/id');
    $terminal->state()->serverTime('account-id');

    expect($history[0]['request'])->toHaveSentRequest('GET', '/users/current/accounts/account-id/account-information');
    expect($history[0]['request']->getUri()->getQuery())->toBe('refreshTerminalState=true');
    expect($history[1]['request'])->toHaveSentRequest('GET', '/users/current/accounts/account-id/positions');
    expect($history[2]['request'])->toHaveSentRequest('GET', '/users/current/accounts/account-id/positions/position%2Fid');
    expect($history[3]['request'])->toHaveSentRequest('GET', '/users/current/accounts/account-id/orders');
    expect($history[4]['request'])->toHaveSentRequest('GET', '/users/current/accounts/account-id/orders/order%2Fid');
    expect($history[5]['request'])->toHaveSentRequest('GET', '/users/current/accounts/account-id/server-time');
});

it('retrieves historical trading data', function (): void {
    $history = [];
    $terminal = terminalWithHistory([
        new Response(200, [], '[]'),
        new Response(200, [], '[]'),
        new Response(200, [], '[]'),
        new Response(200, [], '[]'),
        new Response(200, [], '[]'),
        new Response(200, [], '[]'),
    ], $history);

    $terminal->history()->historyOrdersByTicket('account-id', 'ticket/id');
    $terminal->history()->historyOrdersByPosition('account-id', 'position/id');
    $terminal->history()->historyOrdersByTimeRange('account-id', '2020-09-08 22:21:36.000', '2020-09-09 22:21:36.000', 10, 100);
    $terminal->history()->dealsByTicket('account-id', 'ticket/id');
    $terminal->history()->dealsByPosition('account-id', 'position/id');
    $terminal->history()->dealsByTimeRange('account-id', '2020-09-08 22:21:36.000', '2020-09-09 22:21:36.000');

    expect($history[0]['request'])->toHaveSentRequest('GET', '/users/current/accounts/account-id/history-orders/ticket/ticket%2Fid');
    expect($history[1]['request'])->toHaveSentRequest('GET', '/users/current/accounts/account-id/history-orders/position/position%2Fid');
    expect($history[2]['request'])->toHaveSentRequest('GET', '/users/current/accounts/account-id/history-orders/time/2020-09-08%2022%3A21%3A36.000/2020-09-09%2022%3A21%3A36.000');
    expect($history[2]['request']->getUri()->getQuery())->toBe('offset=10&limit=100');
    expect($history[3]['request'])->toHaveSentRequest('GET', '/users/current/accounts/account-id/history-deals/ticket/ticket%2Fid');
    expect($history[4]['request'])->toHaveSentRequest('GET', '/users/current/accounts/account-id/history-deals/position/position%2Fid');
    expect($history[5]['request'])->toHaveSentRequest('GET', '/users/current/accounts/account-id/history-deals/time/2020-09-08%2022%3A21%3A36.000/2020-09-09%2022%3A21%3A36.000');
});

it('retrieves market data', function (): void {
    $history = [];
    $terminal = terminalWithHistory([
        new Response(200, [], '[]'),
        new Response(200, [], '{"symbol":"EURUSD"}'),
        new Response(200, [], '{"bid":1.1}'),
        new Response(200, [], '{"timeframe":"1m"}'),
        new Response(200, [], '{"symbol":"EURUSD"}'),
        new Response(200, [], '{"bids":[]}'),
        new Response(200, [], '[]'),
        new Response(200, [], '[]'),
    ], $history);

    $terminal->marketData()->symbols('account-id');
    $terminal->marketData()->symbolSpecification('account-id', 'EUR/USD');
    $terminal->marketData()->symbolPrice('account-id', 'EURUSD', true);
    $terminal->marketData()->candle('account-id', 'EURUSD', '1m');
    $terminal->marketData()->tick('account-id', 'EURUSD');
    $terminal->marketData()->orderBook('account-id', 'EURUSD');
    $terminal->marketData()->historicalCandles('account-id', 'EURUSD', '1m', '2020-09-08T22:21:36.000Z', 100);
    $terminal->marketData()->historicalTicks('account-id', 'EURUSD', '2020-09-08T22:21:36.000Z', 10, 100);

    expect($history[0]['request'])->toHaveSentRequest('GET', '/users/current/accounts/account-id/symbols');
    expect($history[1]['request'])->toHaveSentRequest('GET', '/users/current/accounts/account-id/symbols/EUR%2FUSD/specification');
    expect($history[2]['request'])->toHaveSentRequest('GET', '/users/current/accounts/account-id/symbols/EURUSD/current-price');
    expect($history[2]['request']->getUri()->getQuery())->toBe('keepSubscription=true');
    expect($history[3]['request'])->toHaveSentRequest('GET', '/users/current/accounts/account-id/symbols/EURUSD/current-candles/1m');
    expect($history[4]['request'])->toHaveSentRequest('GET', '/users/current/accounts/account-id/symbols/EURUSD/current-tick');
    expect($history[5]['request'])->toHaveSentRequest('GET', '/users/current/accounts/account-id/symbols/EURUSD/current-book');
    expect($history[6]['request'])->toHaveSentRequest('GET', '/users/current/accounts/account-id/historical-market-data/symbols/EURUSD/timeframes/1m/candles');
    expect($history[6]['request']->getUri()->getHost())->toBe('mt-market-data-client-api-v1.new-york.agiliumtrade.ai');
    expect($history[6]['request']->getUri()->getQuery())->toBe('startTime=2020-09-08T22%3A21%3A36.000Z&limit=100');
    expect($history[7]['request'])->toHaveSentRequest('GET', '/users/current/accounts/account-id/historical-market-data/symbols/EURUSD/ticks');
    expect($history[7]['request']->getUri()->getQuery())->toBe('startTime=2020-09-08T22%3A21%3A36.000Z&offset=10&limit=100');
});

it('calculates margin, trades and retrieves CPU credit usage', function (): void {
    $history = [];
    $terminal = terminalWithHistory([
        new Response(200, [], '{"margin":10}'),
        new Response(200, [], '{"stringCode":"TRADE_RETCODE_DONE"}'),
        new Response(200, [], '{"stringCode":"TRADE_RETCODE_DONE"}'),
        new Response(200, [], '{"balance":100}'),
    ], $history);

    $terminal->margin()->calculate('account-id', [
        'symbol' => 'GBPUSD',
        'type' => 'ORDER_TYPE_BUY',
        'volume' => 0.1,
        'openPrice' => 1.25,
    ]);
    $terminal->trading()->trade('account-id', [
        'actionType' => 'ORDER_TYPE_BUY',
        'symbol' => 'EURUSD',
        'volume' => 0.01,
    ]);
    $terminal->trading()->createMarketSellOrder('account-id', 'EURUSD', 0.01);
    $terminal->credits()->usage('account-id');

    expect($history[0]['request'])->toHaveSentRequest('POST', '/users/current/accounts/account-id/calculate-margin');
    expect($history[1]['request'])->toHaveSentRequest('POST', '/users/current/accounts/account-id/trade');
    expect($history[2]['request'])->toHaveSentRequest('POST', '/users/current/accounts/account-id/trade');
    expect(json_decode((string) $history[2]['request']->getBody(), true))->toMatchArray([
        'actionType' => 'ORDER_TYPE_SELL',
        'symbol' => 'EURUSD',
        'volume' => 0.01,
    ]);
    expect($history[3]['request'])->toHaveSentRequest('GET', '/users/current/accounts/account-id/credits');
});
