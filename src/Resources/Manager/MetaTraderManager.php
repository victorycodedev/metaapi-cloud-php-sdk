<?php

namespace Victorycodedev\MetaapiCloudPhpSdk\Resources\Manager;

use Victorycodedev\MetaapiCloudPhpSdk\Http;

abstract class MetaTraderManager
{
    public function __construct(protected readonly Http $http, private readonly int $version) {}

    public function createAccount(string $profileId, array $data): array|string|null
    {
        return $this->http->post($this->profilePath($profileId) . '/accounts', $data);
    }

    public function removeAccount(string $profileId, string|int $login): array|string|null
    {
        return $this->http->delete($this->accountPath($profileId, $login));
    }

    public function setAccountEnabled(string $profileId, string|int $login, bool $enabled): array|string|null
    {
        return $this->http->put($this->accountPath($profileId, $login) . '/enabled', [
            'enabled' => $enabled,
        ]);
    }

    public function groups(string $profileId): array|string|null
    {
        return $this->http->get($this->profilePath($profileId) . '/groups');
    }

    public function accountsByGroup(string $profileId, string $groupName): array|string|null
    {
        return $this->http->get($this->profilePath($profileId) . '/groups/' . Path::segment($groupName) . '/accounts');
    }

    public function accountByLogin(string $profileId, string|int $login): array|string|null
    {
        return $this->http->get($this->accountPath($profileId, $login));
    }

    public function groupByName(string $profileId, string $groupName): array|string|null
    {
        return $this->http->get($this->profilePath($profileId) . '/groups/' . Path::segment($groupName));
    }

    public function balanceOperation(string $profileId, string|int $login, array $data): array|string|null
    {
        return $this->http->post($this->accountPath($profileId, $login) . '/balance-transactions', $data);
    }

    public function accountInformation(string $profileId, string|int $login): array|string|null
    {
        return $this->http->get($this->accountPath($profileId, $login) . '/account-information');
    }

    public function setChangePasswordPermission(string $profileId, string|int $login, bool $changePasswordPermission): array|string|null
    {
        return $this->http->put($this->accountPath($profileId, $login) . '/change-password-permission', [
            'changePasswordPermission' => $changePasswordPermission,
        ]);
    }

    public function changeUserGroup(string $profileId, string|int $login, string $groupName): array|string|null
    {
        return $this->http->put($this->accountPath($profileId, $login) . '/group', [
            'groupName' => $groupName,
        ]);
    }

    public function accountAuthenticationLogs(string $profileId, string|int $login, array $filters = []): array|string|null
    {
        return $this->http->get($this->accountPath($profileId, $login) . '/authentication-logs', $filters);
    }

    public function serverAuthenticationLogs(string $profileId, array $filters = []): array|string|null
    {
        return $this->http->get($this->profilePath($profileId) . '/authentication-logs', $filters);
    }

    public function setAccountReadonly(string $profileId, string|int $login, bool $readonly): array|string|null
    {
        return $this->http->put($this->accountPath($profileId, $login) . '/readonly', [
            'readonly' => $readonly,
        ]);
    }

    public function checkPassword(string $profileId, string|int $login, string $password, ?string $type = null): array|string|null
    {
        return $this->http->post($this->accountPath($profileId, $login) . '/checkPassword', array_filter([
            'password' => $password,
            'type' => $type,
        ], static fn(mixed $value): bool => $value !== null));
    }

    public function symbols(string $profileId, array $filters = []): array|string|null
    {
        return $this->http->get($this->profilePath($profileId) . '/symbols', $filters);
    }

    public function symbolsByGroup(string $profileId, string|int|null $group = null): array|string|null
    {
        return $this->http->get($this->profilePath($profileId) . '/symbolsGroup', [
            'group' => $group,
        ]);
    }

    public function setAccountStatus(string $profileId, string|int $login, string $status): array|string|null
    {
        return $this->http->put($this->accountPath($profileId, $login) . '/status', [
            'status' => $status,
        ]);
    }

    protected function profilePath(string $profileId): string
    {
        return "/users/current/mt{$this->version}/provisioning-profiles/" . Path::segment($profileId);
    }

    protected function accountPath(string $profileId, string|int $login): string
    {
        return $this->profilePath($profileId) . '/accounts/' . Path::segment($login);
    }
}
