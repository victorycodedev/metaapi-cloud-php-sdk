# CopyFactory

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

Configure Copy Trading:

```php
$result = $copyFactory->copyTrade()->configureCopyTrading(
    providerAccountId: 'provider-account-id',
    subscriberAccountId: 'subscriber-account-id',
    strategyId: 'strategy-id',
    strategy: [
        'name' => 'Main strategy',
        'description' => 'Copies my main trading account',
    ],
    subscription: ['multiplier' => 1],
    subscriber: ['name' => 'Main subscriber'],
    validateAccountRoles: false
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

Selected resource methods:

```php
$copyFactory->configuration()->generateStrategyId();
$copyFactory->configuration()->strategies();
$copyFactory->configuration()->updateStrategy('strategy-id', [...]);
$copyFactory->configuration()->portfolioStrategies();
$copyFactory->configuration()->subscribers();

$copyFactory->webhooks()->create('strategy-id', ['url' => 'https://example.com/webhook']);
$copyFactory->history()->providedTransactions();
$copyFactory->trading()->signals('subscriber-id');
$copyFactory->logs()->strategyLog('strategy-id');
```
