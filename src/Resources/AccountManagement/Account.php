<?php

namespace Victorycodedev\MetaapiCloudPhpSdk\Resources\AccountManagement;

use Victorycodedev\MetaapiCloudPhpSdk\Http;

class Account
{
    public function __construct(private readonly Http $http)
    {
    }

    public function readById(string $accountId): array|string|null
    {
        return $this->http->get("/users/current/accounts/{$accountId}");
    }

    public function readAll(array $filters = [], ?int $apiVersion = null): array|string|null
    {
        return $this->accounts($filters, $apiVersion);
    }

    public function accounts(array $filters = [], ?int $apiVersion = null): array|string|null
    {
        return $this->http->get('/users/current/accounts', $filters, $this->apiVersionHeader($apiVersion));
    }

    public function create(array $data, ?string $transactionId = null): array|string|null
    {
        return $this->http->post('/users/current/accounts', $data, $this->transactionHeader($transactionId));
    }

    public function update(string $accountId, array $data): array|string|null
    {
        return $this->http->put("/users/current/accounts/{$accountId}", $data);
    }

    public function unDeploy(string $accountId, bool $executeForAllReplicas = true): array|string|null
    {
        return $this->http->post(
            "/users/current/accounts/{$accountId}/undeploy",
            query: ['executeForAllReplicas' => $executeForAllReplicas]
        );
    }

    public function deploy(string $accountId, bool $executeForAllReplicas = true): array|string|null
    {
        return $this->http->post(
            "/users/current/accounts/{$accountId}/deploy",
            query: ['executeForAllReplicas' => $executeForAllReplicas]
        );
    }

    public function reDeploy(string $accountId, bool $executeForAllReplicas = true): array|string|null
    {
        return $this->http->post(
            "/users/current/accounts/{$accountId}/redeploy",
            query: ['executeForAllReplicas' => $executeForAllReplicas]
        );
    }

    public function delete(string $accountId, bool $executeForAllReplicas = false): array|string|null
    {
        return $this->http->delete(
            "/users/current/accounts/{$accountId}",
            ['executeForAllReplicas' => $executeForAllReplicas]
        );
    }

    public function generateCodeSample(string $accountId, string $platform): array|string|null
    {
        return $this->http->get("/users/current/accounts/{$accountId}/examples/{$platform}");
    }

    public function enableFeatures(string $accountId, array $data): array|string|null
    {
        return $this->http->post("/users/current/accounts/{$accountId}/enable-account-features", $data);
    }

    public function enableCopyFactoryApi(string $accountId, array $copyFactoryRoles, int $copyFactoryResourceSlots = 1): array|string|null
    {
        return $this->http->post("/users/current/accounts/{$accountId}/enable-copy-factory-api", [
            'copyFactoryRoles'          => $copyFactoryRoles,
            'copyFactoryResourceSlots'  => $copyFactoryResourceSlots,
        ]);
    }

    public function createConfigurationLink(string $accountId, ?int $ttlInDays = null): array|string|null
    {
        return $this->http->put(
            "/users/current/accounts/{$accountId}/configuration-link",
            query: ['ttlInDays' => $ttlInDays]
        );
    }

    private function transactionHeader(?string $transactionId): array
    {
        return $transactionId ? ['transaction-id' => $transactionId] : [];
    }

    private function apiVersionHeader(?int $apiVersion): array
    {
        return $apiVersion ? ['api-version' => (string) $apiVersion] : [];
    }
}
