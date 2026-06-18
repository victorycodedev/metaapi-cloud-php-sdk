# MetaApi PHP SDK

A modern PHP SDK for selected MetaApi services:

- MetaTrader account management
- Provisioning profiles
- Account replicas
- MT4/MT5 demo accounts
- Expert advisors
- User quota management
- Known trading servers
- MetaApi REST terminal API
- CopyFactory
- MetaStats

This package does not yet wrap every MetaApi API, but the exposed services are organized around the current SDK entry point and resource classes.

Real-time streaming/WebSocket support is planned for a future version.

## Requirements

- PHP 8.2 or newer

## Installation

```bash
composer require victorycodedev/metaapi-cloud-php-sdk
```

## Quick Start

Use `MetaApiClient` as the main SDK entry point:

```php
use Victorycodedev\MetaapiCloudPhpSdk\MetaApiClient;
use Victorycodedev\MetaapiCloudPhpSdk\Exceptions\MetaApiException;

$metaapi = new MetaApiClient('AUTH_TOKEN');

try {
    $accounts = $metaapi->accounts()->accounts([
        'limit' => 100,
        'offset' => 0,
    ]);
} catch (MetaApiException $exception) {
    echo $exception->getMessage();
    echo $exception->statusCode();

    print_r($exception->response());
}
```

All failed MetaApi responses throw `MetaApiException`. The exception includes the HTTP status code, parsed MetaApi error response, response headers, error id, error name, details and retry-after header when available.

The SDK applies MetaApi authentication headers automatically, including when you inject a custom Guzzle client.

## Regions

Account Management uses MetaApi's global provisioning URL and does not require a region.

Regional services default to `new-york` and accept region names:

```php
$copyFactory = $metaapi->copyFactory(region: 'london');
$terminal = $metaapi->terminal(region: 'london');
$metaStats = $metaapi->metaStats(region: 'london');
```

For private/custom regions, pass the service URL:

```php
$copyFactory = $metaapi->copyFactory(
    serverUrl: 'https://copyfactory-api-v1.my-region.example.com'
);

$terminal = $metaapi->terminal(
    serverUrl: 'https://mt-client-api-v1.my-region.example.com',
    marketDataServerUrl: 'https://mt-market-data-client-api-v1.my-region.example.com'
);
```

## Account Management

```php
$accounts = $metaapi->accounts();
```

Create an account:

```php
try {
    $response = $accounts->create([
        'login' => '123456',
        'password' => 'password',
        'name' => 'Main MT5 account',
        'server' => 'ICMarketsSC-Demo',
        'platform' => 'mt5',
        'magic' => 123456,
        'type' => 'cloud-g2',
    ]);

    if ($response->isCreated()) {
        echo "Account created: " . $response->id();
    }

    if ($response->isAccepted()) {
        echo "Account creation accepted. Retry after: " . ($response->retryAfter() ?? 'not specified');
    }
} catch (MetaApiException $exception) {
    echo $exception->getMessage();
}
```

`accounts()->create()` returns an `ActionResponse` because MetaApi can respond with `201 Created` or `202 Accepted`.

```php
$response->body();
$response->statusCode();
$response->headers();
$response->retryAfter();
$response->isCreated();
$response->isAccepted();
$response->shouldRetry();
$response->id();
$response->state();
```

Read accounts:

```php
$allAccounts = $accounts->accounts([
    'deploymentStatus' => ['deployed'],
    'type' => ['cloud-g2'],
    'limit' => 100,
    'offset' => 0,
], apiVersion: 2);

$account = $accounts->readById('account-id');
```

Update and lifecycle operations:

```php
$accounts->update('account-id', [
    'name' => 'Updated account name',
    'password' => 'new-password',
]);

$accounts->deploy('account-id');
$accounts->undeploy('account-id');
$accounts->redeploy('account-id');
$accounts->delete('account-id', executeForAllReplicas: true);
```

Enable account features/APIs:

```php
$accounts->enableFeatures('account-id', [
    'metastatsApiEnabled' => true,
    'riskManagementApiEnabled' => true,
    'reliabilityIncreased' => true,
    'copyFactoryApi' => [
        'copyFactoryRoles' => ['PROVIDER'],
        'copyFactoryResourceSlots' => 1,
    ],
]);
```

Create a secure configuration link:

```php
$link = $accounts->createConfigurationLink('account-id', ttlInDays: 3);
```

## Provisioning Profiles

```php
$profiles = $metaapi->provisioningProfiles();
```

```php
$created = $profiles->createProvisioningProfile([
    'name' => 'ICMarkets MT5',
    'version' => 5,
    'brokerTimezone' => 'EET',
    'brokerDSTSwitchTimezone' => 'EET',
    'type' => 'mtTerminal',
]);

$profiles->uploadProvisioningProfileFile(
    $created['id'],
    'servers.dat',
    '/path/to/servers.dat'
);

$profile = $profiles->provisioningProfile($created['id']);
```

Available methods:

- `provisioningProfiles(array $filters = [], ?int $apiVersion = null)`
- `provisioningProfile(string $profileId)`
- `createProvisioningProfile(array $data)`
- `uploadProvisioningProfileFile(string $profileId, string $fileName, string $filePath)`
- `updateProvisioningProfile(string $profileId, array $data)`
- `deleteProvisioningProfile(string $profileId)`

## Account Replicas

```php
$replicas = $metaapi->accountReplicas();
```

```php
$response = $replicas->createReplica(
    'primary-account-id',
    [
        'magic' => 123456,
        'region' => 'london',
    ],
);

if ($response->shouldRetry()) {
    echo "Replica creation accepted. Retry after: " . ($response->retryAfter() ?? 'not specified');
}
```

A transaction ID is auto-generated. Pass your own `transactionId` to retry an accepted request.

Available methods:

- `replicas(string $accountId)`
- `replica(string $accountId, string $replicaId)`
- `createReplica(string $accountId, array $data, ?string $transactionId = null)` returns `ActionResponse`
- `updateReplica(string $accountId, string $replicaId, array $data)`
- `undeployReplica(string $accountId, string $replicaId)`
- `deployReplica(string $accountId, string $replicaId)`
- `redeployReplica(string $accountId, string $replicaId)`
- `deleteReplica(string $accountId, string $replicaId)`
- `generateReplicaCodeSample(string $accountId, string $replicaId, string $platform)`
- `increaseReplicaReliability(string $accountId, string $replicaId)`

## Demo Accounts

```php
$demoAccounts = $metaapi->demoAccounts();
```

Create a MetaTrader 4 demo account:

```php
$response = $demoAccounts->createMT4DemoAccount(
    'provisioning-profile-id',
    [
        'accountType' => 'type',
        'balance' => 1000,
        'email' => 'user@example.com',
        'leverage' => 10,
        'name' => 'Test User',
        'phone' => '+12345678901',
        'serverName' => 'Example-Server',
        'keywords' => ['Example Broker Ltd'],
    ],
);

if ($response->isCreated()) {
    echo "Demo account created. Login: " . $response->body()['login'];
}

if ($response->shouldRetry()) {
    echo "Request accepted. Retry after: " . ($response->retryAfter() ?? 'not specified');
}
```

A transaction ID is auto-generated for you. You can also pass your own if you need to retry an accepted request:

```php
$response = $demoAccounts->createMT4DemoAccount(
    'provisioning-profile-id',
    [...],
    transactionId: 'my-custom-32-char-transaction-id'
);
```

Create a MetaTrader 5 demo account:

```php
$response = $demoAccounts->createMT5DemoAccount(
    'provisioning-profile-id',
    [
        'accountType' => 'type',
        'balance' => 1000,
        'email' => 'user@example.com',
        'leverage' => 10,
        'name' => 'Test User',
        'phone' => '+12345678901',
        'serverName' => 'Example-Server',
        'keywords' => ['Example Broker Ltd'],
    ],
);
```

Both methods return `ActionResponse` — the same type used by `accounts()->create()`.

## Expert Advisors

```php
$eas = $metaapi->expertAdvisors();
```

```php
// List all expert advisors on an account
$advisors = $eas->expertAdvisors('account-id');

// Read a specific expert advisor
$advisor = $eas->expertAdvisor('account-id', 'expert-id');

// Update or create an expert advisor
$eas->updateExpertAdvisor('account-id', 'expert-id', [
    'symbol' => 'EURUSD',
    'period' => '1H',
    'preset' => 'base64-encoded-preset',
]);

// Upload an expert advisor file
$eas->uploadExpertAdvisorFile('account-id', 'expert-id', '/path/to/expert.ex5');

// Delete an expert advisor
$eas->deleteExpertAdvisor('account-id', 'expert-id');
```

Available methods:

- `expertAdvisors(string $accountId)`
- `expertAdvisor(string $accountId, string $expertId)`
- `updateExpertAdvisor(string $accountId, string $expertId, array $data)`
- `uploadExpertAdvisorFile(string $accountId, string $expertId, string $filePath)`
- `deleteExpertAdvisor(string $accountId, string $expertId)`

## Quota

```php
$quota = $metaapi->quotas();
```

```php
// Get user quota and usage for all regions
$quotas = $quota->quotas();

// Get quota update requests
$updateRequests = $quota->quotaUpdateRequests();

// Request a region quota update
$quota->requestRegionQuotaUpdate('vint-hill', [
    'maxAccounts' => 150,
    'maxDeployedG1Accounts' => 25,
    'maxDeployedG2Accounts' => 120,
    'maxDeployedCopyFactoryAccounts' => 120,
    'maxDedicatedIpv4' => 100,
    'justification' => 'quota update request justification message',
]);
```

Available methods:

- `quotas()`
- `quotaUpdateRequests()`
- `requestRegionQuotaUpdate(string $region, array $data)`

## Mt Servers

```php
$mtServers = $metaapi->mtServers();
```

```php
// Search known trading servers by MT version
$servers = $mtServers->knownTradingServers(5, 'icmarketssc');
```

Available methods:

- `knownTradingServers(int $version, string $query)`

## CopyFactory

```php
$copyFactory = $metaapi->copyFactory(region: 'london');
```

CopyFactory is grouped by resource:

```php
$copyFactory->configuration();
$copyFactory->webhooks();
$copyFactory->history();
$copyFactory->trading();
$copyFactory->logs();
$copyFactory->copyTrade();
```

Configuration:

```php
$strategyId = $copyFactory->configuration()->generateStrategyId();

$strategies = $copyFactory->configuration()->strategies(
    includeRemoved: false,
    limit: 1000,
    offset: 0
);

$copyFactory->configuration()->updateStrategy('strategy-id', [
    'name' => 'Main strategy',
    'description' => 'Copies my main account',
    'accountId' => 'provider-account-id',
]);

$copyFactory->configuration()->portfolioStrategies();

$copyFactory->configuration()->updateSubscriber('subscriber-account-id', [
    'name' => 'Main subscriber',
    'subscriptions' => [
        [
            'strategyId' => 'strategy-id',
            'multiplier' => 1,
        ],
    ],
]);
```

Webhooks:

```php
$copyFactory->webhooks()->create('strategy-id', [
    'url' => 'https://example.com/copyfactory/webhook',
]);

$copyFactory->webhooks()->list('strategy-id');
$copyFactory->webhooks()->update('strategy-id', 'webhook-id', ['enabled' => true]);
$copyFactory->webhooks()->remove('strategy-id', 'webhook-id');
```

History:

```php
$copyFactory->history()->providedTransactions([
    'from' => '2020-04-20T04:00:00.000Z',
    'till' => '2020-04-20T04:30:00.000Z',
]);

$copyFactory->history()->subscriptionTransactions([
    'from' => '2020-04-20T04:00:00.000Z',
    'till' => '2020-04-20T04:30:00.000Z',
]);

$copyFactory->history()->strategyTransactionsStream('strategy-id');
$copyFactory->history()->subscriberTransactionsStream('subscriber-id');
```

Trading:

```php
$copyFactory->trading()->signals('subscriber-id');
$copyFactory->trading()->externalSignals('strategy-id');

$copyFactory->trading()->updateExternalSignal('strategy-id', 'signal-id', [
    'symbol' => 'EURUSD',
]);

$copyFactory->trading()->removeExternalSignal('strategy-id', 'signal-id', [
    'time' => '2020-08-24T00:00:00.000Z',
]);

$copyFactory->trading()->stopouts('subscriber-id');
$copyFactory->trading()->resetSubscriptionStopouts(
    'subscriber-id',
    'strategy-id',
    'day-balance-difference'
);
$copyFactory->trading()->resetSubscriberStopouts(
    'subscriber-id',
    'day-balance-difference'
);
$copyFactory->trading()->stopoutsStream();
$copyFactory->trading()->resynchronize('subscriber-id');
```

Logs:

```php
$copyFactory->logs()->userLog('subscriber-id');
$copyFactory->logs()->userLogStream('subscriber-id');
$copyFactory->logs()->strategyLog('strategy-id');
$copyFactory->logs()->strategyLogStream('strategy-id');
```

## Configure Copy Trading

This configures CopyFactory to copy trades from a provider account into a subscriber account.

For production apps, create and store a strategy id yourself, then pass it into the helper. This avoids extra lookup requests.

```php
$result = $copyFactory->copyTrade()->configureCopyTrading(
    providerAccountId: 'provider-account-id',
    subscriberAccountId: 'subscriber-account-id',
    strategyId: 'strategy-id',
    strategy: [
        'name' => 'Main strategy',
        'description' => 'Copies my main trading account',
    ],
    subscription: [
        'multiplier' => 1,
    ],
    subscriber: [
        'name' => 'Main subscriber',
    ],
    validateAccountRoles: false
);
```

If you omit `strategyId`, the SDK generates one. It does not list all strategies unless you explicitly enable reuse:

```php
$result = $copyFactory->copyTrade()->configureCopyTrading(
    providerAccountId: 'provider-account-id',
    subscriberAccountId: 'subscriber-account-id',
    reuseExistingStrategy: true
);
```

If you already fetched account data, pass it in to skip account reads:

```php
$result = $copyFactory->copyTrade()->configureCopyTrading(
    providerAccountId: 'provider-public-id',
    subscriberAccountId: 'subscriber-public-id',
    providerAccount: [
        '_id' => 'provider-internal-id',
        'copyFactoryRoles' => ['PROVIDER'],
    ],
    subscriberAccount: [
        '_id' => 'subscriber-internal-id',
        'copyFactoryRoles' => ['SUBSCRIBER'],
    ],
);
```

For a quick setup, `copy()` is available as a shortcut:

```php
$result = $copyFactory->copy(
    'provider-account-id',
    'subscriber-account-id',
    'strategy-id'
);
```

The shortcut delegates to `configureCopyTrading()`. Use the full method when you need custom strategy/subscriber payloads, validation control or reuse behavior.

## MetaApi REST Terminal API

```php
$terminal = $metaapi->terminal(region: 'london');
```

The REST terminal API is grouped by resource:

```php
$terminal->state();
$terminal->history();
$terminal->marketData();
$terminal->margin();
$terminal->trading();
$terminal->credits();
```

Read trading terminal state:

```php
$accountInformation = $terminal->state()->accountInformation('account-id');
$positions = $terminal->state()->positions('account-id');
$position = $terminal->state()->position('account-id', 'position-id');
$orders = $terminal->state()->orders('account-id');
$order = $terminal->state()->order('account-id', 'order-id');
$serverTime = $terminal->state()->serverTime('account-id');
```

Retrieve historical data:

```php
$ordersByTicket = $terminal->history()->historyOrdersByTicket('account-id', 'ticket');
$ordersByPosition = $terminal->history()->historyOrdersByPosition('account-id', 'position-id');

$ordersByTime = $terminal->history()->historyOrdersByTimeRange(
    'account-id',
    '2020-09-08 22:21:36.000',
    '2020-09-09 22:21:36.000',
    offset: 0,
    limit: 1000
);

$dealsByTicket = $terminal->history()->dealsByTicket('account-id', 'ticket');
$dealsByPosition = $terminal->history()->dealsByPosition('account-id', 'position-id');
$dealsByTime = $terminal->history()->dealsByTimeRange(
    'account-id',
    '2020-09-08 22:21:36.000',
    '2020-09-09 22:21:36.000'
);
```

Retrieve market data:

```php
$symbols = $terminal->marketData()->symbols('account-id');
$specification = $terminal->marketData()->symbolSpecification('account-id', 'EURUSD');
$price = $terminal->marketData()->symbolPrice('account-id', 'EURUSD', keepSubscription: true);
$candle = $terminal->marketData()->candle('account-id', 'EURUSD', '1m');
$tick = $terminal->marketData()->tick('account-id', 'EURUSD');
$book = $terminal->marketData()->orderBook('account-id', 'EURUSD');

$historicalCandles = $terminal->marketData()->historicalCandles(
    'account-id',
    'EURUSD',
    '1m',
    startTime: '2020-09-08T22:21:36.000Z',
    limit: 1000
);

$historicalTicks = $terminal->marketData()->historicalTicks(
    'account-id',
    'EURUSD',
    startTime: '2020-09-08T22:21:36.000Z',
    offset: 0,
    limit: 1000
);
```

Calculate margin:

```php
$margin = $terminal->margin()->calculate('account-id', [
    'symbol' => 'GBPUSD',
    'type' => 'ORDER_TYPE_BUY',
    'volume' => 0.1,
    'openPrice' => 1.25,
]);
```

Trade:

```php
$result = $terminal->trading()->trade('account-id', [
    'actionType' => 'ORDER_TYPE_BUY',
    'symbol' => 'EURUSD',
    'volume' => 0.01,
    'stopLoss' => 1.09,
    'takeProfit' => 1.11,
]);

$terminal->trading()->createMarketBuyOrder('account-id', 'EURUSD', 0.01);
$terminal->trading()->createMarketSellOrder('account-id', 'EURUSD', 0.01);
$terminal->trading()->createLimitBuyOrder('account-id', 'EURUSD', 0.01, 1.08);
$terminal->trading()->createStopSellOrder('account-id', 'EURUSD', 0.01, 1.07);
$terminal->trading()->createStopLimitBuyOrder('account-id', 'EURUSD', 0.01, 1.08, 1.081);
$terminal->trading()->modifyPosition('account-id', 'position-id', stopLoss: 1.09);
$terminal->trading()->closePosition('account-id', 'position-id');
$terminal->trading()->closePositionPartially('account-id', 'position-id', 0.01);
$terminal->trading()->closePositionsBySymbol('account-id', 'EURUSD');
$terminal->trading()->cancelOrder('account-id', 'order-id');
```

Retrieve CPU credit usage:

```php
$credits = $terminal->credits()->usage('account-id');
```

## MetaStats

```php
$stats = $metaapi->metaStats(region: 'london');
```

Calculate metrics:

```php
$metrics = $stats->metrics('account-id', includeOpenPositions: true);
```

Get historical trades:

```php
$trades = $stats->historicalTrades(
    'account-id',
    '2020-09-08 22:21:36.000',
    '2020-09-09 22:21:36.000',
    [
        'limit' => 1000,
        'offset' => 0,
        'updateHistory' => true,
    ]
);
```

Get open trades:

```php
$openTrades = $stats->openTrades('account-id');
```

Reset metrics and trade history:

```php
$stats->resetMetrics('account-id');
```

## Error Handling

```php
use Victorycodedev\MetaapiCloudPhpSdk\Exceptions\MetaApiException;

try {
    $account = $metaapi->accounts()->readById('account-id');
} catch (MetaApiException $exception) {
    echo $exception->message();
    echo $exception->statusCode();
    echo $exception->id();
    echo $exception->error();

    print_r($exception->details());
    print_r($exception->toArray());
    print_r($exception->response());
}
```

`MetaApiException` reflects MetaApi's standard REST error model:

```php
$exception->id();       // Error id
$exception->error();    // Error name, e.g. ValidationError
$exception->message();  // User-friendly error description
$exception->details();  // Additional validation details
$exception->toArray();  // id, error, message and details
```

The existing `errorId()`, `errorName()` and `getMessage()` methods remain available for backward compatibility. HTTP response errors and transport failures from custom Guzzle clients are normalized to `MetaApiException`; the original transport exception is available through `getPrevious()`.

## Testing

```bash
composer test
```

## API Reference

All API references can be found on the MetaApi documentation website: https://metaapi.cloud/

## Security

If you discover any security related issues, please open an issue.

## Contributing

Pull requests are welcome.

## How Can I Thank You?

Why not star the GitHub repo? You can also share the link for this repository on Twitter or HackerNews.

Follow me on X: https://x.com/efekpoguavik3

Thanks! Efekpogua Victory.

## License

The MIT License (MIT). Please see [License File](./LICENSE.md) for more information.
