# MT Manager API

The MT Manager API is for brokers or broker partners who have manager access to MT4/MT5 servers.

```php
$manager = $metaapi->manager(region: 'london');

$mt4 = $manager->mt4();
$mt5 = $manager->mt5();
```

For private/custom regions:

```php
$manager = $metaapi->manager(
    serverUrl: 'https://mt-manager-api-v1.my-region.example.com'
);
```

## MT4

```php
$mt4->createAccount('profile-id', [
    'email' => 'user@example.com',
    'group' => 'Exness-Trial4',
    'leverage' => 100,
]);

$mt4->removeAccount('profile-id', 12345);
$mt4->setAccountEnabled('profile-id', 12345, true);
$mt4->groups('profile-id');
$mt4->accountsByGroup('profile-id', 'Example\\Group');
$mt4->accountByLogin('profile-id', 12345);
$mt4->groupByName('profile-id', 'Example\\Group');
$mt4->balanceOperation('profile-id', 12345, [
    'amount' => 100,
    'type' => 'OP_BALANCE',
    'comment' => 'Deposit',
]);
$mt4->changePassword('profile-id', 12345, 'new-password', 'master');
$mt4->accountInformation('profile-id', 12345);
$mt4->setChangePasswordPermission('profile-id', 12345, true);
$mt4->changeUserGroup('profile-id', 12345, 'Live');
$mt4->accountAuthenticationLogs('profile-id', 12345, [
    'from' => '2020-08-24 00:00:00.000',
    'to' => '2020-08-25 00:00:00.000',
]);
$mt4->serverAuthenticationLogs('profile-id');
$mt4->setAccountReadonly('profile-id', 12345, false);
$mt4->checkPassword('profile-id', 12345, 'password');
$mt4->symbols('profile-id', [
    'included' => 'EUR.*',
    'excluded' => '.*micro',
]);
$mt4->symbolsByGroup('profile-id', 1);
$mt4->setAccountStatus('profile-id', 12345, 'ACTIVE');
```

Available MT4 methods:

- `createAccount(string $profileId, array $data)`
- `removeAccount(string $profileId, string|int $login)`
- `setAccountEnabled(string $profileId, string|int $login, bool $enabled)`
- `groups(string $profileId)`
- `accountsByGroup(string $profileId, string $groupName)`
- `accountByLogin(string $profileId, string|int $login)`
- `groupByName(string $profileId, string $groupName)`
- `balanceOperation(string $profileId, string|int $login, array $data)`
- `changePassword(string $profileId, string|int $login, string $password, string $type = 'master')`
- `accountInformation(string $profileId, string|int $login)`
- `setChangePasswordPermission(string $profileId, string|int $login, bool $changePasswordPermission)`
- `changeUserGroup(string $profileId, string|int $login, string $groupName)`
- `accountAuthenticationLogs(string $profileId, string|int $login, array $filters = [])`
- `serverAuthenticationLogs(string $profileId, array $filters = [])`
- `setAccountReadonly(string $profileId, string|int $login, bool $readonly)`
- `checkPassword(string $profileId, string|int $login, string $password, ?string $type = null)`
- `symbols(string $profileId, array $filters = [])`
- `symbolsByGroup(string $profileId, string|int|null $group = null)`
- `setAccountStatus(string $profileId, string|int $login, string $status)`

## MT5

MT5 supports the same methods as MT4 and also supports updating trading permission:

```php
$mt5->createAccount('profile-id', [
    'email' => 'user@example.com',
    'group' => 'Demo',
    'leverage' => 100,
]);

$mt5->setAccountTrading('profile-id', 12345, true);
```

Available MT5 methods:

- `createAccount(string $profileId, array $data)`
- `removeAccount(string $profileId, string|int $login)`
- `setAccountEnabled(string $profileId, string|int $login, bool $enabled)`
- `groups(string $profileId)`
- `accountsByGroup(string $profileId, string $groupName)`
- `accountByLogin(string $profileId, string|int $login)`
- `groupByName(string $profileId, string $groupName)`
- `balanceOperation(string $profileId, string|int $login, array $data)`
- `accountInformation(string $profileId, string|int $login)`
- `setChangePasswordPermission(string $profileId, string|int $login, bool $changePasswordPermission)`
- `changeUserGroup(string $profileId, string|int $login, string $groupName)`
- `accountAuthenticationLogs(string $profileId, string|int $login, array $filters = [])`
- `serverAuthenticationLogs(string $profileId, array $filters = [])`
- `setAccountReadonly(string $profileId, string|int $login, bool $readonly)`
- `setAccountTrading(string $profileId, string|int $login, bool $trading)`
- `checkPassword(string $profileId, string|int $login, string $password, ?string $type = null)`
- `symbols(string $profileId, array $filters = [])`
- `symbolsByGroup(string $profileId, string|int|null $group = null)`
- `setAccountStatus(string $profileId, string|int $login, string $status)`
