# Custom HTTP Client

You can inject a custom Guzzle client when constructing the SDK:

```php
use GuzzleHttp\Client;
use Victorycodedev\MetaapiCloudPhpSdk\MetaApiClient;

$guzzle = new Client([
    'timeout' => 30,
    'connect_timeout' => 10,
]);

$metaapi = new MetaApiClient('AUTH_TOKEN', $guzzle);
```

The SDK still applies the required MetaApi headers automatically:

- `auth-token`
- `Accept: application/json`
- `Content-Type: application/json` for JSON requests

The SDK also normalizes HTTP errors and transport failures to `MetaApiException`, even when a custom Guzzle client is used.
