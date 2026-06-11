# Metaapi PHP sdk

A PHP Package that let you seamlessly perform api call to Metapapi https://metaapi.cloud/
NOTE: This package does not include all api calls in Metapi.
You can do CopyTrade, Account Managment and Metrics.

## Requirements

- PHP 8.2 or newer
- Guzzle 7

## Installation

To install the SDK in your project you need to install the package via composer:

```bash
composer require victorycodedev/metaapi-cloud-php-sdk
```

## Usage

The preferred entry point is the root client:

```php
use Victorycodedev\MetaapiCloudPhpSdk\MetaApiClient;

$metaapi = new MetaApiClient('AUTH_TOKEN');

$accounts = $metaapi->accounts();
$profiles = $metaapi->provisioningProfiles();
$replicas = $metaapi->accountReplicas();
```

Regional APIs default to `new-york` and accept region names directly:

```php
$copyFactory = $metaapi->copyFactory(region: 'london');
$metaStats = $metaapi->metaStats(region: 'london');
```

For private/custom regions, pass the full service URL:

```php
$copyFactory = $metaapi->copyFactory(serverUrl: 'https://copyfactory-api-v1.my-region.example.com');
```

CopyFactory is organized into resource groups:

```php
$copyFactory = $metaapi->copyFactory(region: 'london');

$copyFactory->configuration()->strategies();
$copyFactory->configuration()->portfolioStrategies();
$copyFactory->webhooks()->create('strategy-id', ['magic' => 100]);
$copyFactory->history()->providedTransactions([
    'from' => '2020-04-20T04:00:00.000Z',
    'till' => '2020-04-20T04:30:00.000Z',
]);
$copyFactory->trading()->signals('subscriber-id');
$copyFactory->logs()->userLog('subscriber-id');
```

The common older shortcut methods on `CopyFactory` are still available, for example:

```php
$copyFactory->strategies();
$copyFactory->subscribers();
$copyFactory->copy('provider-account-id', 'subscriber-account-id');
```

You can still use the previous `AccountApi`, `CopyFactory` and `MetaStats` classes directly. `AccountApi` now acts as a backwards-compatible facade over the new account management resources.

## Account Management

You can create an instance of the SDK like so for Account Management:
```php
use Victorycodedev\MetaapiCloudPhpSdk\AccountApi;

$account = new AccountApi('AUTH_TOKEN');

```

All methods throws exceptions when the request is not successful, so be sure to put your code in a try and catch block.

```php
 when statusCode >= 200 && statusCode < 300;
```

You can add a trading account and starts a cloud API server for the trading account like so:

```php

try {
    return $account->create([
        "login" => "123456", 
        "password" => "password", 
        "name" => "testAccount", 
        "server" => "ICMarketsSC-Demo", 
        "platform" => "mt5", 
        "magic" => 123456 
    ]);
} catch (\Throwable $th) {
    $response = json_decode($th->getMessage());
    return $response->message;
}

```
if the request was successful , you will get the the account id and state, else an Exception will be thrown

```php
    [
        "id" => "1eda642a-a9a3-457c-99af-3bc5e8d5c4c9", 
        "state" => "DEPLOYED" 
    ]
```

You can read an account by the id

```php

try {
     return $account->readById("1eda642a-a9a3-457c-99af-3bc5e8d5c4c9");
} catch (\Throwable $th) {
    $response = json_decode($th->getMessage());
    return $response->message;
}

```


You can read all trading accounts in your metaapi account

```php

try {
    return $account->readAll();

    // You can also pass MetaApi filters and request api-version 2 list responses
    return $account->accounts([
        'deploymentStatus' => ['deployed'],
        'type' => ['cloud-g2'],
        'limit' => 100,
        'offset' => 0,
    ], apiVersion: 2);
} catch (\Throwable $th) {
    $response = json_decode($th->getMessage());
    return $response->message;
}

```

You can update an account

```php

try {
    return  $account->update("1eda642a-a9a3-457c-99af-3bc5e8d5c4c9",[
        "password" => "password", 
        "name" => "testAccount", 
        "server" => "ICMarketsSC-Demo", 
    ]);
} catch (\Throwable $th) {
    $response = json_decode($th->getMessage());
    return $response->message;
}

```

Undeploy an account

```php

try {
    return $account->unDeploy("1eda642a-a9a3-457c-99af-3bc5e8d5c4c9");
    // you can pass other parameters 
    return $account->unDeploy("1eda642a-a9a3-457c-99af-3bc5e8d5c4c9", false);
} catch (\Throwable $th) {
    $response = json_decode($th->getMessage());
    return $response->message;
}

```

Deploy an account

```php

try {
    return $account->deploy("1eda642a-a9a3-457c-99af-3bc5e8d5c4c9");
     // you can pass other parameters 
    return $account->deploy("1eda642a-a9a3-457c-99af-3bc5e8d5c4c9", false);
} catch (\Throwable $th) {
    $response = json_decode($th->getMessage());
    return $response->message;
}

```

Redeploy an account

```php

try {
    return $account->reDeploy("1eda642a-a9a3-457c-99af-3bc5e8d5c4c9");
     // you can pass other parameters 
    return $account->reDeploy("1eda642a-a9a3-457c-99af-3bc5e8d5c4c9", false);
} catch (\Throwable $th) {
    $response = json_decode($th->getMessage());
    return $response->message;
}

```

Delete an account

```php

try {
    return $account->delete("1eda642a-a9a3-457c-99af-3bc5e8d5c4c9");
     // you can pass other parameters 
    return $account->delete("1eda642a-a9a3-457c-99af-3bc5e8d5c4c9", true);
} catch (\Throwable $th) {
    $response = json_decode($th->getMessage());
    return $response->message;
}

```

Enable paid account features or APIs:

```php
try {
    return $account->enableFeatures("1eda642a-a9a3-457c-99af-3bc5e8d5c4c9", [
        'metastatsApiEnabled' => true,
        'riskManagementApiEnabled' => true,
        'reliabilityIncreased' => true,
        'allocateDedicatedIp' => 'ipv4',
        'copyFactoryApi' => [
            'copyFactoryRoles' => ['PROVIDER'],
            'copyFactoryResourceSlots' => 1,
        ],
    ]);
} catch (\Throwable $th) {
    $response = json_decode($th->getMessage());
    return $response->message;
}
```

Create a secure trading account configuration link:

```php
try {
    return $account->createConfigurationLink("1eda642a-a9a3-457c-99af-3bc5e8d5c4c9", ttlInDays: 3);
} catch (\Throwable $th) {
    $response = json_decode($th->getMessage());
    return $response->message;
}
```

## Provisioning Profiles

Provisioning profiles are available via the root client or `AccountApi`:

```php
use Victorycodedev\MetaapiCloudPhpSdk\MetaApiClient;

$profiles = (new MetaApiClient('AUTH_TOKEN'))->provisioningProfiles();

try {
    $created = $profiles->createProvisioningProfile([
        'name' => 'ICMarkets MT5',
        'version' => 5,
        'brokerTimezone' => 'EET',
        'brokerDSTSwitchTimezone' => 'EET',
        'type' => 'mtTerminal',
    ]);

    $profiles->uploadProvisioningProfileFile($created['id'], 'servers.dat', '/path/to/servers.dat');
    return $profiles->provisioningProfile($created['id']);
} catch (\Throwable $th) {
    $response = json_decode($th->getMessage());
    return $response->message;
}
```

Available provisioning profile methods:

- `provisioningProfiles(array $filters = [], ?int $apiVersion = null)`
- `provisioningProfile(string $profileId)`
- `createProvisioningProfile(array $data)`
- `uploadProvisioningProfileFile(string $profileId, string $fileName, string $filePath)`
- `updateProvisioningProfile(string $profileId, array $data)`
- `deleteProvisioningProfile(string $profileId)`

## Account Replicas

Account replicas are available via the root client or `AccountApi`:

```php
$replicas = (new MetaApiClient('AUTH_TOKEN'))->accountReplicas();

try {
    return $replicas->createReplica(
        'primary-account-id',
        [
            'magic' => 123456,
            'region' => 'london',
        ],
        transactionId: bin2hex(random_bytes(16))
    );
} catch (\Throwable $th) {
    $response = json_decode($th->getMessage());
    return $response->message;
}
```

Available account replica methods:

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

You can create an instance of the SDK like so for Copyfactory:
```php
use Victorycodedev\MetaapiCloudPhpSdk\CopyFactory;

$copyfactory = new CopyFactory('AUTH_TOKEN');

```

To generate a strategy id

```php

try {
   return $copyfactory->generateStrategyId();
} catch (\Throwable $th) {
    $response = json_decode($th->getMessage());
    return $response->message;
}

```

To get all your strategies 

```php

try {
   return $copyfactory->strategies();
    //you can also pass in other parameters like so
    return $copyfactory->strategies(includeRemoved: true, limit: 1000, offset: 0 );
} catch (\Throwable $th) {
    $response = json_decode($th->getMessage());
    return $response->message;
}

```

To get a single strategy 

```php

try {
   return $copyfactory->strategy("strategid");
} catch (\Throwable $th) {
    $response = json_decode($th->getMessage());
    return $response->message;
}

```

To update a strategy 

```php

try {
   return $copyfactory->updateStrategy("strategid", [
        "name" => "Test strategy", 
        "description" => "Some useful description about your strategy", 
        "accountId" => "105646d8-8c97-4d4d-9b74-413bd66cd4ed" 
   ]);
} catch (\Throwable $th) {
    $response = json_decode($th->getMessage());
    return $response->message;
}

```

To remove a strategy 

```php

try {
   return $copyfactory->removeStrategy("strategid");
} catch (\Throwable $th) {
    $response = json_decode($th->getMessage());
    return $response->message;
}

```

To get all your subscribers

```php

try {
   return $copyfactory->subscribers();
    //you can also pass in other parameters like so
    return $copyfactory->subscribers(includeRemoved: true, limit: 1000, offset: 0 );
} catch (\Throwable $th) {
    $response = json_decode($th->getMessage());
    return $response->message;
}

```

To get a subscriber

```php

try {
   return $copyfactory->subscriber("subscriberiId");
} catch (\Throwable $th) {
    $response = json_decode($th->getMessage());
    return $response->message;
}

```

To update a subscriber data

```php

try {
   return $copyfactory->updateSubscriber("subsciberId", [
        'name' => "Copy Trade Subscriber",
        'subscriptions' => [
            [
                'strategyId' => 'dJZq',
                'multiplier' => 1,
            ]
        ]
    ]);
} catch (\Throwable $th) {
    $response = json_decode($th->getMessage());
    return $response->message;
}

```

To remove a subscriber

```php

try {
   return $copyfactory->removeSubscriber("subsciberId");
} catch (\Throwable $th) {
    $response = json_decode($th->getMessage());
    return $response->message;
}

```

To delete a subscription

```php

try {
   return $copyfactory->deleteSubscription("subsciberId", "strategyId");
} catch (\Throwable $th) {
    $response = json_decode($th->getMessage());
    return $response->message;
}

```

## Copy Trade

This configures CopyFactory to copy trades from a provider account into a subscriber account.
For production apps, it is best to create and store a strategy id yourself, then pass it into the helper. This avoids extra lookup requests.

Fast path:

```php

try {
    $strategyId = "yd24";
    $providerAccountId = "Enter your provider account ID";
    $subAccountId = "Enter Subscriber Account ID";

    return $copyfactory->copyTrade()->configureCopyTrading(
        providerAccountId: $providerAccountId,
        subscriberAccountId: $subAccountId,
        strategyId: $strategyId,
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
} catch (\Throwable $th) {
    $response = json_decode($th->getMessage());
    return $response->message;
}

```

You can omit the strategy id. In that case the SDK generates one. It does not list all strategies unless you explicitly ask it to reuse an existing strategy.

```php
try {
    return $copyfactory->copyTrade()->configureCopyTrading(
        providerAccountId: $providerAccountId,
        subscriberAccountId: $subAccountId
    );
} catch (\Throwable $th) {
    $response = json_decode($th->getMessage());
    return $response->message;
}
```

If you want the SDK to look for an existing strategy for the provider account first:

```php
try {
    return $copyfactory->copyTrade()->configureCopyTrading(
        providerAccountId: $providerAccountId,
        subscriberAccountId: $subAccountId,
        reuseExistingStrategy: true
    );
} catch (\Throwable $th) {
    $response = json_decode($th->getMessage());
    return $response->message;
}
```

The older shortcut still works:

```php
try {
    return $copyfactory->copy($providerAccountId, $subAccountId, $strategyId);
} catch (\Throwable $th) {
    $response = json_decode($th->getMessage());
    return $response->message;
}

```

## MetaStats

You can get metrics, historical trades, open trades and reset metrics for your account.

Preferred usage:

```php
use Victorycodedev\MetaapiCloudPhpSdk\MetaApiClient;

$stats = (new MetaApiClient('AUTH_TOKEN'))->metaStats(region: 'london');
```

You can also create an instance of the SDK like so for MetaStats:

```php
use Victorycodedev\MetaapiCloudPhpSdk\MetaStats;

$stats = new MetaStats('AUTH_TOKEN');
```

To get metrics: 

```php

try {
   return  $stats->metrics("accountId");
    //  You can pass a boolean as second parameter if you want to include open positions in your metrics
     return  $stats->metrics("accountId", true);
} catch (\Throwable $th) {
    $response = json_decode($th->getMessage());
    return $response->message;
}

```

To get historical trades for a MetaApi account:

```php
try {
   return $stats->historicalTrades(
      "accountId",
      "2020-09-08 22:21:36.000",
      "2020-09-09 22:21:36.000",
      [
          "limit" => 1000,
          "offset" => 0,
          "updateHistory" => true,
      ]
   );
} catch (\Throwable $th) {
    $response = json_decode($th->getMessage());
    return $response->message;
}
```

To get open trades for MetaApi account:

```php

try {
   return  $stats->openTrades("accountId");
} catch (\Throwable $th) {
    $response = json_decode($th->getMessage());
    return $response->message;
}

```

To reset metrics and trade history:

```php
try {
   return $stats->resetMetrics("accountId");
} catch (\Throwable $th) {
    $response = json_decode($th->getMessage());
    return $response->message;
}
```

## Testing 

```php

composer test

```

## API Reference
All API references can be found on Metaapi documentation website. https://metaapi.cloud/

## Security
If you discover any security related issues, please open an issue.

## Contributing

Pull requests are welcome. 

## How can I thank you?
Why not star the github repo? I'd love the attention! you can share the link for this repository on Twitter or HackerNews? 

Don't forget to [follow me on twitter!](https://x.com/efekpoguavik3)

Thanks! Efekpogua Victory.

## License

The MIT License (MIT). Please see [License File](./LICENSE.md) or more information.
