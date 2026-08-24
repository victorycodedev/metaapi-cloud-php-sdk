# MetaApi PHP SDK

A modern PHP SDK for MetaApi services.

## Supported APIs

- MetaTrader account management
- Provisioning profiles
- Account replicas
- MT4/MT5 demo accounts
- Expert advisors
- User quota management
- Known trading servers
- MetaApi REST terminal API
- CopyFactory
- MetaStats
- MT Manager API
- Risk Management API

Real-time streaming/WebSocket support is planned for a future version.

## Requirements

- PHP 8.2 or newer

## Installation

```bash
composer require victorycodedev/metaapi-cloud-php-sdk
```

## Quick Start

Use `MetaApiClient` as the main SDK entry point:

```php
use Victorycodedev\MetaapiCloudPhpSdk\MetaApiClient;
use Victorycodedev\MetaapiCloudPhpSdk\Exceptions\MetaApiException;

$metaapi = new MetaApiClient('AUTH_TOKEN');

try {
    $accounts = $metaapi->accounts()->accounts([
        'limit' => 100,
        'offset' => 0,
    ]);
} catch (MetaApiException $exception) {
    echo $exception->message();
    echo $exception->statusCode();

    print_r($exception->toArray());
}
```

All failed MetaApi responses throw `MetaApiException`. The exception exposes MetaApi's standard error fields using `id()`, `error()`, `message()`, `details()` and `toArray()`.

## Services

```php
$accounts = $metaapi->accounts();
$profiles = $metaapi->provisioningProfiles();
$replicas = $metaapi->accountReplicas();
$terminal = $metaapi->terminal(region: 'london');
$copyFactory = $metaapi->copyFactory(region: 'london');
$metaStats = $metaapi->metaStats(region: 'london');
$manager = $metaapi->manager(region: 'london');
$risk = $metaapi->riskManagement(region: 'london');
```

Account Management uses MetaApi's global provisioning URL and does not require a region. Regional services default to `new-york`.

For private/custom regions, pass the service URL:

```php
$manager = $metaapi->manager(
    serverUrl: 'https://mt-manager-api-v1.my-region.example.com'
);

$risk = $metaapi->riskManagement(
    serverUrl: 'https://risk-management-api-v1.my-region.example.com'
);
```

## Documentation

- [Account Management](docs/account-management.md)
- [Provisioning Profiles](docs/provisioning-profiles.md)
- [Account Replicas](docs/account-replicas.md)
- [Demo Accounts, Expert Advisors, Quotas and MT Servers](docs/account-extras.md)
- [MetaApi REST Terminal API](docs/terminal-api.md)
- [CopyFactory](docs/copyfactory.md)
- [MetaStats](docs/metastats.md)
- [MT Manager API](docs/mt-manager.md)
- [Risk Management API](docs/risk-management.md)
- [Error Handling](docs/error-handling.md)
- [Custom HTTP Client](docs/custom-http-client.md)
- [Roadmap](docs/roadmap.md)

## Testing

```bash
composer test
```

## API Reference

All official API references can be found on the MetaApi documentation website: https://metaapi.cloud/

## Security

If you discover any security related issues, please open an issue.

## Contributing

Pull requests are welcome.

## How Can I Thank You?

Why not star the GitHub repo? You can also share the link for this repository on Twitter or HackerNews.

Follow me on X: https://x.com/efekpoguavik3

Thanks! Efekpogua Victory.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
