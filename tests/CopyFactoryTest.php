<?php

use GuzzleHttp\Psr7\Response;
use Victorycodedev\MetaapiCloudPhpSdk\Exceptions\MetaApiException;

it('supports portfolio strategy endpoints', function (): void {
    $history = [];
    $copyFactory = copyFactoryWithHistory([
        new Response(200, [], '[]'),
        new Response(200, [], '{}'),
        new Response(204),
        new Response(204),
        new Response(204),
    ], $history);

    $copyFactory->configuration()->portfolioStrategies(limit: 25, apiVersion: 2);
    $copyFactory->configuration()->portfolioStrategy('portfolio-id');
    $copyFactory->configuration()->updatePortfolioStrategy('portfolio-id', ['name' => 'Portfolio']);
    $copyFactory->configuration()->removePortfolioStrategy('portfolio-id', ['closePositions' => true]);
    $copyFactory->configuration()->removePortfolioStrategyMember('portfolio-id', 'strategy-id');

    expect($history[0]['request'])->toHaveSentRequest('GET', '/users/current/configuration/portfolio-strategies');
    expect($history[0]['request']->getHeaderLine('api-version'))->toBe('2');
    expect($history[1]['request'])->toHaveSentRequest('GET', '/users/current/configuration/portfolio-strategies/portfolio-id');
    expect($history[2]['request'])->toHaveSentRequest('PUT', '/users/current/configuration/portfolio-strategies/portfolio-id');
    expect($history[3]['request'])->toHaveSentRequest('DELETE', '/users/current/configuration/portfolio-strategies/portfolio-id');
    expect($history[4]['request'])->toHaveSentRequest('DELETE', '/users/current/configuration/portfolio-strategies/portfolio-id/members/strategy-id');
});

it('supports webhook endpoints', function (): void {
    $history = [];
    $copyFactory = copyFactoryWithHistory([
        new Response(200, [], '[]'),
        new Response(201, [], '{"id":"webhook-id"}'),
        new Response(204),
        new Response(204),
    ], $history);

    $copyFactory->webhooks()->list('strategy-id', ['paginationStyle' => 'classic', 'limit' => 10]);
    $copyFactory->webhooks()->create('strategy-id', ['magic' => 100]);
    $copyFactory->webhooks()->update('strategy-id', 'webhook-id', ['magic' => 101]);
    $copyFactory->webhooks()->remove('strategy-id', 'webhook-id');

    expect($history[0]['request'])->toHaveSentRequest('GET', '/users/current/configuration/strategies/strategy-id/webhooks');
    expect($history[0]['request']->getUri()->getQuery())->toContain('paginationStyle=classic');
    expect($history[1]['request'])->toHaveSentRequest('POST', '/users/current/configuration/strategies/strategy-id/webhooks');
    expect($history[2]['request'])->toHaveSentRequest('PATCH', '/users/current/configuration/strategies/strategy-id/webhooks/webhook-id');
    expect($history[3]['request'])->toHaveSentRequest('DELETE', '/users/current/configuration/strategies/strategy-id/webhooks/webhook-id');
});

it('supports history endpoints', function (): void {
    $history = [];
    $copyFactory = copyFactoryWithHistory([
        new Response(200, [], '[]'),
        new Response(200, [], '[]'),
        new Response(200, [], '[]'),
        new Response(200, [], '[]'),
    ], $history);

    $query = ['from' => '2020-04-20T04:00:00.000Z', 'till' => '2020-04-20T04:30:00.000Z'];
    $copyFactory->history()->providedTransactions($query);
    $copyFactory->history()->subscriptionTransactions($query);
    $copyFactory->history()->strategyTransactionsStream('strategy-id', ['startTime' => '2020-04-20T04:00:00.000Z']);
    $copyFactory->history()->subscriberTransactionsStream('subscriber-id', ['limit' => 50]);

    expect($history[0]['request'])->toHaveSentRequest('GET', '/users/current/provided-transactions');
    expect($history[1]['request'])->toHaveSentRequest('GET', '/users/current/subscription-transactions');
    expect($history[2]['request'])->toHaveSentRequest('GET', '/users/current/strategies/strategy-id/transactions/stream');
    expect($history[3]['request'])->toHaveSentRequest('GET', '/users/current/subscribers/subscriber-id/transactions/stream');
});

it('supports trading endpoints', function (): void {
    $history = [];
    $copyFactory = copyFactoryWithHistory(array_fill(0, 9, new Response(200, [], '[]')), $history);

    $copyFactory->trading()->signals('subscriber-id');
    $copyFactory->trading()->externalSignals('strategy-id');
    $copyFactory->trading()->updateExternalSignal('strategy-id', 'signal-id', ['symbol' => 'EURUSD']);
    $copyFactory->trading()->removeExternalSignal('strategy-id', 'signal-id', ['time' => '2020-08-24T00:00:00.000Z']);
    $copyFactory->trading()->stopouts('subscriber-id');
    $copyFactory->trading()->resetSubscriptionStopouts('subscriber-id', 'strategy-id', 'day-balance-difference');
    $copyFactory->trading()->resetSubscriberStopouts('subscriber-id', 'day-balance-difference');
    $copyFactory->trading()->stopoutsStream(['subscriberId' => 'subscriber-id']);
    $copyFactory->trading()->resynchronize('subscriber-id', ['strategyId' => ['strategy-id']]);

    expect($history[0]['request'])->toHaveSentRequest('GET', '/users/current/subscribers/subscriber-id/signals');
    expect($history[1]['request'])->toHaveSentRequest('GET', '/users/current/strategies/strategy-id/external-signals');
    expect($history[2]['request'])->toHaveSentRequest('PUT', '/users/current/strategies/strategy-id/external-signals/signal-id');
    expect($history[3]['request'])->toHaveSentRequest('POST', '/users/current/strategies/strategy-id/external-signals/signal-id/remove');
    expect($history[4]['request'])->toHaveSentRequest('GET', '/users/current/subscribers/subscriber-id/stopouts');
    expect($history[5]['request'])->toHaveSentRequest('POST', '/users/current/subscribers/subscriber-id/subscription-strategies/strategy-id/stopouts/day-balance-difference/reset');
    expect($history[6]['request'])->toHaveSentRequest('POST', '/users/current/subscribers/subscriber-id/stopouts/day-balance-difference/reset');
    expect($history[7]['request'])->toHaveSentRequest('GET', '/users/current/stopouts/stream');
    expect($history[8]['request'])->toHaveSentRequest('POST', '/users/current/subscribers/subscriber-id/resynchronize');
});

it('supports log endpoints', function (): void {
    $history = [];
    $copyFactory = copyFactoryWithHistory(array_fill(0, 4, new Response(200, [], '[]')), $history);

    $copyFactory->logs()->userLog('subscriber-id', ['limit' => 10]);
    $copyFactory->logs()->userLogStream('subscriber-id', ['startTime' => '2020-04-20T04:00:00.000Z']);
    $copyFactory->logs()->strategyLog('strategy-id', ['offset' => 10]);
    $copyFactory->logs()->strategyLogStream('strategy-id', ['limit' => 50]);

    expect($history[0]['request'])->toHaveSentRequest('GET', '/users/current/subscribers/subscriber-id/user-log');
    expect($history[1]['request'])->toHaveSentRequest('GET', '/users/current/subscribers/subscriber-id/user-log/stream');
    expect($history[2]['request'])->toHaveSentRequest('GET', '/users/current/strategies/strategy-id/user-log');
    expect($history[3]['request'])->toHaveSentRequest('GET', '/users/current/strategies/strategy-id/user-log/stream');
});

it('configures copy trading with the fast path when strategy id is supplied and validation is disabled', function (): void {
    $history = [];
    $copyFactory = copyFactoryWithHistory([
        new Response(204),
        new Response(204),
    ], $history);

    $response = $copyFactory->copyTrade()->configureCopyTrading(
        providerAccountId: 'provider-account-id',
        subscriberAccountId: 'subscriber-account-id',
        strategyId: 'strategy-id',
        strategy: ['name' => 'Main strategy'],
        subscription: ['multiplier' => 2],
        subscriber: ['name' => 'Main subscriber'],
        validateAccountRoles: false
    );

    expect($response)->toMatchArray([
        'strategyId' => 'strategy-id',
        'providerAccountId' => 'provider-account-id',
        'subscriberAccountId' => 'subscriber-account-id',
    ]);
    expect($history)->toHaveCount(2);
    expect($history[0]['request'])->toHaveSentRequest('PUT', '/users/current/configuration/strategies/strategy-id');
    expect($history[1]['request'])->toHaveSentRequest('PUT', '/users/current/configuration/subscribers/subscriber-account-id');
});

it('generates a strategy id without listing strategies by default', function (): void {
    $history = [];
    $copyFactory = copyFactoryWithHistory([
        new Response(200, [], '{"_id":"provider-account-id","copyFactoryRoles":["PROVIDER"]}'),
        new Response(200, [], '{"_id":"subscriber-account-id","copyFactoryRoles":["SUBSCRIBER"]}'),
        new Response(200, [], '{"id":"generated-strategy-id"}'),
        new Response(204),
        new Response(204),
    ], $history);

    $response = $copyFactory->copyTrade()->configureCopyTrading(
        providerAccountId: 'provider-account-id',
        subscriberAccountId: 'subscriber-account-id'
    );

    expect($response['strategyId'])->toBe('generated-strategy-id');
    expect($history)->toHaveCount(5);
    expect($history[0]['request'])->toHaveSentRequest('GET', '/users/current/accounts/provider-account-id');
    expect($history[1]['request'])->toHaveSentRequest('GET', '/users/current/accounts/subscriber-account-id');
    expect($history[2]['request'])->toHaveSentRequest('GET', '/users/current/configuration/unused-strategy-id');
    expect($history[3]['request'])->toHaveSentRequest('PUT', '/users/current/configuration/strategies/generated-strategy-id');
    expect($history[4]['request'])->toHaveSentRequest('PUT', '/users/current/configuration/subscribers/subscriber-account-id');
});

it('only lists strategies when reuse is explicitly enabled', function (): void {
    $history = [];
    $copyFactory = copyFactoryWithHistory([
        new Response(200, [], '{"_id":"provider-account-id","copyFactoryRoles":["PROVIDER"]}'),
        new Response(200, [], '{"_id":"subscriber-account-id","copyFactoryRoles":["SUBSCRIBER"]}'),
        new Response(200, [], '[{"_id":"existing-strategy-id","accountId":"provider-account-id"}]'),
        new Response(204),
        new Response(204),
    ], $history);

    $response = $copyFactory->copyTrade()->configureCopyTrading(
        providerAccountId: 'provider-account-id',
        subscriberAccountId: 'subscriber-account-id',
        reuseExistingStrategy: true
    );

    expect($response['strategyId'])->toBe('existing-strategy-id');
    expect($history[2]['request'])->toHaveSentRequest('GET', '/users/current/configuration/strategies');
    expect($history[3]['request'])->toHaveSentRequest('PUT', '/users/current/configuration/strategies/existing-strategy-id');
});

it('can skip account reads when account data is supplied', function (): void {
    $history = [];
    $copyFactory = copyFactoryWithHistory([
        new Response(200, [], '{"id":"generated-strategy-id"}'),
        new Response(204),
        new Response(204),
    ], $history);

    $copyFactory->copyTrade()->configureCopyTrading(
        providerAccountId: 'provider-public-id',
        subscriberAccountId: 'subscriber-public-id',
        providerAccount: ['_id' => 'provider-internal-id', 'copyFactoryRoles' => ['PROVIDER']],
        subscriberAccount: ['_id' => 'subscriber-internal-id', 'copyFactoryRoles' => ['SUBSCRIBER']]
    );

    expect($history)->toHaveCount(3);
    expect($history[0]['request'])->toHaveSentRequest('GET', '/users/current/configuration/unused-strategy-id');
    expect($history[1]['request'])->toHaveSentRequest('PUT', '/users/current/configuration/strategies/generated-strategy-id');
    expect($history[2]['request'])->toHaveSentRequest('PUT', '/users/current/configuration/subscribers/subscriber-internal-id');
});

it('throws MetaApiException for local copy trading validation errors', function (): void {
    $copyFactory = copyFactoryWithHistory([]);

    $copyFactory->copyTrade()->configureCopyTrading(
        providerAccountId: 'provider-account-id',
        subscriberAccountId: 'subscriber-account-id',
        providerAccount: ['_id' => 'provider-internal-id', 'copyFactoryRoles' => []],
        subscriberAccount: ['_id' => 'subscriber-internal-id', 'copyFactoryRoles' => ['SUBSCRIBER']]
    );
})->throws(MetaApiException::class, 'Account provider-account-id is not a PROVIDER account');
