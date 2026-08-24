# MetaApi REST Terminal API

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
$terminal->state()->accountInformation('account-id');
$terminal->state()->positions('account-id');
$terminal->state()->position('account-id', 'position-id');
$terminal->state()->orders('account-id');
$terminal->state()->order('account-id', 'order-id');
$terminal->state()->serverTime('account-id');
```

Retrieve historical data:

```php
$terminal->history()->historyOrdersByTicket('account-id', 'ticket');
$terminal->history()->historyOrdersByPosition('account-id', 'position-id');
$terminal->history()->historyOrdersByTimeRange(
    'account-id',
    '2020-09-08 22:21:36.000',
    '2020-09-09 22:21:36.000',
    offset: 0,
    limit: 1000
);
$terminal->history()->dealsByTicket('account-id', 'ticket');
$terminal->history()->dealsByPosition('account-id', 'position-id');
$terminal->history()->dealsByTimeRange(
    'account-id',
    '2020-09-08 22:21:36.000',
    '2020-09-09 22:21:36.000'
);
```

Retrieve market data:

```php
$terminal->marketData()->symbols('account-id');
$terminal->marketData()->symbolSpecification('account-id', 'EURUSD');
$terminal->marketData()->symbolPrice('account-id', 'EURUSD', keepSubscription: true);
$terminal->marketData()->candle('account-id', 'EURUSD', '1m');
$terminal->marketData()->tick('account-id', 'EURUSD');
$terminal->marketData()->orderBook('account-id', 'EURUSD');
$terminal->marketData()->historicalCandles('account-id', 'EURUSD', '1m');
$terminal->marketData()->historicalTicks('account-id', 'EURUSD');
```

Calculate margin and trade:

```php
$terminal->margin()->calculate('account-id', [
    'symbol' => 'GBPUSD',
    'type' => 'ORDER_TYPE_BUY',
    'volume' => 0.1,
    'openPrice' => 1.25,
]);

$terminal->trading()->trade('account-id', [
    'actionType' => 'ORDER_TYPE_BUY',
    'symbol' => 'EURUSD',
    'volume' => 0.01,
]);

$terminal->trading()->createMarketBuyOrder('account-id', 'EURUSD', 0.01);
$terminal->trading()->createMarketSellOrder('account-id', 'EURUSD', 0.01);
$terminal->trading()->modifyPosition('account-id', 'position-id', stopLoss: 1.09);
$terminal->trading()->closePosition('account-id', 'position-id');
$terminal->trading()->cancelOrder('account-id', 'order-id');
```

Retrieve CPU credit usage:

```php
$credits = $terminal->credits()->usage('account-id');
```
