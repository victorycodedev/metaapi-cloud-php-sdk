# Account Management

```php
$accounts = $metaapi->accounts();
```

Create an account:

```php
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
```

`accounts()->create()` returns an `ActionResponse` because MetaApi can respond with `201 Created` or `202 Accepted`.

Available response helpers:

- `body()`
- `statusCode()`
- `headers()`
- `retryAfter()`
- `isCreated()`
- `isAccepted()`
- `shouldRetry()`
- `id()`
- `state()`

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
$accounts->update('account-id', ['name' => 'Updated account name']);
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
