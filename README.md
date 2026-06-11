# MetaApi PHP SDK

A modern PHP SDK for selected MetaApi services:

- MetaTrader account management
- Provisioning profiles
- Account replicas
- CopyFactory
- MetaStats

This package does not yet wrap every MetaApi API, but the exposed services are organized around the current SDK entry point and resource classes.

## Requirements

- PHP 8.2 or newer
- Guzzle 7

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

## Regions

Account Management uses MetaApi's global provisioning URL and does not require a region.

Regional services default to `new-york` and accept region names:

```php
$copyFactory = $metaapi->copyFactory(region: 'london');
$metaStats = $metaapi->metaStats(region: 'london');
```

For private/custom regions, pass the service URL:

```php
$copyFactory = $metaapi->copyFactory(
    serverUrl: 'https://copyfactory-api-v1.my-region.example.com'
);
```

## Account Management

```php
$accounts = $metaapi->accounts();
```

Create an account:

```php
try {
    $account = $accounts->create([
        'login' => '123456',
        'password' => 'password',
        'name' => 'Main MT5 account',
        'server' => 'ICMarketsSC-Demo',
        'platform' => 'mt5',
        'magic' => 123456,
        'type' => 'cloud-g2',
    ]);
} catch (MetaApiException $exception) {
    echo $exception->getMessage();
}
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
$accounts->unDeploy('account-id');
$accounts->reDeploy('account-id');
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
$replica = $replicas->createReplica(
    'primary-account-id',
    [
        'magic' => 123456,
        'region' => 'london',
    ],
    transactionId: bin2hex(random_bytes(16))
);
```

Available methods:

- `replicas(string $accountId)`
- `replica(string $accountId, string $replicaId)`
- `createReplica(string $accountId, array $data, ?string $transactionId = null)`
- `updateReplica(string $accountId, string $replicaId, array $data)`
- `undeployReplica(string $accountId, string $replicaId)`
- `deployReplica(string $accountId, string $replicaId)`
- `redeployReplica(string $accountId, string $replicaId)`
- `deleteReplica(string $accountId, string $replicaId)`
- `generateReplicaCodeSample(string $accountId, string $replicaId, string $platform)`
- `increaseReplicaReliability(string $accountId, string $replicaId)`

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
    echo $exception->getMessage();
    echo $exception->statusCode();
    echo $exception->errorName();

    print_r($exception->details());
    print_r($exception->response());
}
```

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
