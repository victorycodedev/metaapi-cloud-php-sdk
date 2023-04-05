# Metaapi PHP sdk

A PHP Package that let's seamlessly perform api call to Metapapi https://metaapi.cloud/
NOTE: This package does not include all api calls in Metapi.
This package mainly focuses on the copytrade feature of metaapi, but it can also perform other functions.

## Installation

To install the SDK in your project you need to install the package via composer:

```bash
composer require victorycodedev/metaapi-cloud-php-sdk
```

## Usage: Account Management

You can create an instance of the SDK like so for Account Management:
```php
use Victorycodedev\MetaapiCloudPhpSdk\AccountApi;

$account = new AccountApi('AUTH_TOKEN');

```

All methods throws exceptions when the request is not successful, so be sure to use try and catch in your code.

```php
 when statusCode >= 200 && statusCode < 300;
```

You can add a trading account and starts a cloud API server for the trading account like so:

```php

try {
    return $anct = $account->create([
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


You can read all accounts in your metaapi

```php

try {
    return $account->readAll();
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
## Usage: CopyFactory

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

To get a single  strategy 

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

To Copy a trade from provider to subscriber.
I recommend you create a stratgy before hand and save to your database before you perform a copy trade, but its not compulsory
as the package will create one for you. You can always read all your strategies in your account with this package. 

To Copy trade do: 

```php

try {
    $strategyId = "yd24";
    $providerAccountId = "Enter your provider account ID";
    $subAccountId = "Enter Subscriber Account ID";

    return $copyfactory->copy($providerAccountId, $subAccountId, $stragyId);

    /*
        You can ommit the strategy Id and just copy the trade 
    */

    return $copyfactory->copy($providerAccountId, $subAccountId);

} catch (\Throwable $th) {
    $response = json_decode($th->getMessage());
    return $response->message;
}

```

## Usage: MetaStats

You can get metrics for you account

You can create an instance of the SDK like so for Copyfactory:
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


To get open trades for MetaApi account:

```php

try {
   return  $stats->openTrades("accountId");
} catch (\Throwable $th) {
    $response = json_decode($th->getMessage());
    return $response->message;
}

```

## Contributing

Pull requests are welcome. For major changes, please open an issue first
to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License

[MIT](./LICENSE.md)
