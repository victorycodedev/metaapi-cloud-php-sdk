# Provisioning Profiles

```php
$profiles = $metaapi->provisioningProfiles();
```

```php
$created = $profiles->createProvisioningProfile([
    'name' => 'ICMarkets MT5',
    'version' => 5,
    'brokerTimezone' => 'EET',
    'brokerDSTSwitchTimezone' => 'EET',
    'type' => 'mtTerminal',
]);

$profiles->uploadProvisioningProfileFile(
    $created['id'],
    'servers.dat',
    '/path/to/servers.dat'
);

$profile = $profiles->provisioningProfile($created['id']);
```

Available methods:

- `provisioningProfiles(array $filters = [], ?int $apiVersion = null)`
- `provisioningProfile(string $profileId)`
- `createProvisioningProfile(array $data)`
- `uploadProvisioningProfileFile(string $profileId, string $fileName, string $filePath)`
- `updateProvisioningProfile(string $profileId, array $data)`
- `deleteProvisioningProfile(string $profileId)`
