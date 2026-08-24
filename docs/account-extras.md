# Account Extras

## Demo Accounts

```php
$demoAccounts = $metaapi->demoAccounts();

$response = $demoAccounts->createMT4DemoAccount('provisioning-profile-id', [
    'accountType' => 'type',
    'balance' => 1000,
    'email' => 'user@example.com',
    'leverage' => 10,
    'name' => 'Test User',
    'phone' => '+12345678901',
    'serverName' => 'Example-Server',
    'keywords' => ['Example Broker Ltd'],
]);

$demoAccounts->createMT5DemoAccount('provisioning-profile-id', [...]);
```

Both methods return `ActionResponse`.

## Expert Advisors

```php
$eas = $metaapi->expertAdvisors();

$eas->expertAdvisors('account-id');
$eas->expertAdvisor('account-id', 'expert-id');
$eas->updateExpertAdvisor('account-id', 'expert-id', [
    'symbol' => 'EURUSD',
    'period' => '1H',
    'preset' => 'base64-encoded-preset',
]);
$eas->uploadExpertAdvisorFile('account-id', 'expert-id', '/path/to/expert.ex5');
$eas->deleteExpertAdvisor('account-id', 'expert-id');
```

## Quota

```php
$quota = $metaapi->quotas();

$quota->quotas();
$quota->quotaUpdateRequests();
$quota->requestRegionQuotaUpdate('vint-hill', [
    'maxAccounts' => 150,
    'justification' => 'quota update request justification message',
]);
```

## MT Servers

```php
$servers = $metaapi->mtServers()->knownTradingServers(5, 'icmarketssc');
```
