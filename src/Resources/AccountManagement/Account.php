<?php

namespace Victorycodedev\MetaapiCloudPhpSdk\Resources\AccountManagement;

trait Account
{
    public function readById(string $accountId): array|string
    {
        return $this->http->get("{$this->baseUrl}/users/current/accounts/{$accountId}");
    }

    public function readAll(): array|string
    {
        return $this->http->get("{$this->baseUrl}/users/current/accounts");
    }

    public function create(array $data): array|string
    {
        return $this->http->post("{$this->baseUrl}/users/current/accounts", $data);
    }

    public function update(string $accountId, array $data): array|string
    {
        return $this->http->put("{$this->baseUrl}/users/current/accounts/{$accountId}", $data);
    }

    public function unDeploy(string $accountId, bool $executeForAllReplicas = true): array|string
    {
        return $this->http->post("{$this->baseUrl}/users/current/accounts/{$accountId}/undeploy?executeForAllReplicas={$executeForAllReplicas}");
    }

    public function deploy(string $accountId, bool $executeForAllReplicas = true): array|string
    {
        return $this->http->post("{$this->baseUrl}/users/current/accounts/{$accountId}/deploy?executeForAllReplicas={$executeForAllReplicas}");
    }

    public function reDeploy(string $accountId, bool $executeForAllReplicas = true): array|string
    {
        return $this->http->post("{$this->baseUrl}/users/current/accounts/{$accountId}/redeploy?executeForAllReplicas={$executeForAllReplicas}");
    }

    public function delete(string $accountId, bool $executeForAllReplicas = false): array|string
    {
        return $this->http->delete("{$this->baseUrl}/users/current/accounts/{$accountId}/redeploy?executeForAllReplicas={$executeForAllReplicas}");
    }
}
