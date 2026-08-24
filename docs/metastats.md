# MetaStats

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

Get open trades and reset metrics:

```php
$openTrades = $stats->openTrades('account-id');
$stats->resetMetrics('account-id');
```
