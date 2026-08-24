# Error Handling

```php
use Victorycodedev\MetaapiCloudPhpSdk\Exceptions\MetaApiException;

try {
    $account = $metaapi->accounts()->readById('account-id');
} catch (MetaApiException $exception) {
    echo $exception->message();
    echo $exception->statusCode();
    echo $exception->id();
    echo $exception->error();

    print_r($exception->details());
    print_r($exception->toArray());
    print_r($exception->response());
}
```

`MetaApiException` reflects MetaApi's standard REST error model:

```php
$exception->id();       // Error id
$exception->error();    // Error name, e.g. ValidationError
$exception->message();  // User-friendly error description
$exception->details();  // Additional validation details
$exception->toArray();  // id, error, message and details
```

The existing `errorId()`, `errorName()` and `getMessage()` methods remain available for backward compatibility. HTTP response errors and transport failures from custom Guzzle clients are normalized to `MetaApiException`; the original transport exception is available through `getPrevious()`.
