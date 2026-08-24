# Risk Management API

The Risk Management API is useful for prop trading challenges, competitions, equity tracking, drawdown limits and profit targets.

```php
$risk = $metaapi->riskManagement(region: 'london');
$trackers = $risk->trackers();
```

For private/custom regions:

```php
$risk = $metaapi->riskManagement(
    serverUrl: 'https://risk-management-api-v1.my-region.example.com'
);
```

Create a tracker:

```php
$created = $trackers->create('account-id', [
    'name' => 'Daily drawdown',
    'startBrokerTime' => '2020-08-24 00:00:00.000',
    'endBrokerTime' => '2020-08-25 00:00:00.000',
    'period' => 'day',
    'relativeDrawdownThreshold' => 0.1,
    'absoluteDrawdownThreshold' => 10000,
    'relativeProfitThreshold' => 0.05,
    'absoluteProfitThreshold' => 5000,
]);
```

Read trackers:

```php
$all = $trackers->trackers('account-id');
$tracker = $trackers->tracker('account-id', 'tracker-id');
$byName = $trackers->trackerByName('account-id', 'Daily drawdown');
```

Events, statistics and equity chart:

```php
$events = $trackers->events([
    'startBrokerTime' => '2020-08-24 00:00:00.000',
    'endBrokerTime' => '2020-08-25 00:00:00.000',
    'accountId' => 'account-id',
    'trackerId' => 'tracker-id',
    'limit' => 1000,
]);

$streamEvents = $trackers->eventStream([
    'previousSequenceNumber' => 0,
    'accountId' => 'account-id',
    'trackerId' => 'tracker-id',
]);

$stats = $trackers->statistics('account-id', 'tracker-id', [
    'startTime' => '2020-08-24 00:00:00.000',
    'limit' => 10,
    'realTime' => true,
]);

$chart = $trackers->equityChart('account-id');
```

Update and remove:

```php
$trackers->update('account-id', 'tracker-id', [
    'name' => 'Updated tracker name',
]);

$trackers->remove('account-id', 'tracker-id');
```

MetaApi notes that some Risk Management endpoints plan a future default API version change. The SDK sends `api-version: 1` by default for versioned tracker endpoints. Pass `apiVersion: null` if you want to omit the header.
