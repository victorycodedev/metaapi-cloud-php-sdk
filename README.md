# Metaapi PHP sdk

A PHP Package that let's seamlessly perform api call to Metapapi https://metaapi.cloud/
NOTE: This package does not include all api calls in Metapi.
This package mainly focuses on the copytrade feature of metaapi, but it can also perform other functions.

## Installation

To install the SDK in your project you need to install the package via composer:

```bash
composer require victorycodedev/metaapi-cloud-php-sdk
```

## Usage

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
    $anct = $account->create([
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
if the request was successful , you will get the the account id and state

```php
    [
        "id" => "1eda642a-a9a3-457c-99af-3bc5e8d5c4c9", 
        "state" => "DEPLOYED" 
    ]
```

## Contributing

Pull requests are welcome. For major changes, please open an issue first
to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License

[MIT](./LICENSE.md)
