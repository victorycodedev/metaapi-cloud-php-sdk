<?php

namespace Victorycodedev\MetaapiCloudPhpSdk\Resources\AccountManagement;

use Victorycodedev\MetaapiCloudPhpSdk\Http;
use Victorycodedev\MetaapiCloudPhpSdk\Responses\ActionResponse;

class AccountReplica
{
    public function __construct(private readonly Http $http) {}

    public function replicas(string $accountId): array|string|null
    {
        return $this->http->get("/users/current/accounts/{$accountId}/replicas");
    }

    public function replica(string $accountId, string $replicaId): array|string|null
    {
        return $this->http->get("/users/current/accounts/{$accountId}/replicas/{$replicaId}");
    }

    public function createReplica(string $accountId, array $data, ?string $transactionId = null): ActionResponse
    {
        return $this->http->postAction(
            "/users/current/accounts/{$accountId}/replicas",
            $data,
            $transactionId ? ['transaction-id' => $transactionId] : []
        );
    }

    public function updateReplica(string $accountId, string $replicaId, array $data): array|string|null
    {
        return $this->http->put("/users/current/accounts/{$accountId}/replicas/{$replicaId}", $data);
    }

    public function undeployReplica(string $accountId, string $replicaId): array|string|null
    {
        return $this->http->post("/users/current/accounts/{$accountId}/replicas/{$replicaId}/undeploy");
    }

    public function deployReplica(string $accountId, string $replicaId): array|string|null
    {
        return $this->http->post("/users/current/accounts/{$accountId}/replicas/{$replicaId}/deploy");
    }

    public function redeployReplica(string $accountId, string $replicaId): array|string|null
    {
        return $this->http->post("/users/current/accounts/{$accountId}/replicas/{$replicaId}/redeploy");
    }

    public function deleteReplica(string $accountId, string $replicaId): array|string|null
    {
        return $this->http->delete("/users/current/accounts/{$accountId}/replicas/{$replicaId}");
    }

    public function generateReplicaCodeSample(string $accountId, string $replicaId, string $platform): array|string|null
    {
        return $this->http->get("/users/current/accounts/{$accountId}/replicas/{$replicaId}/examples/{$platform}");
    }

    public function increaseReplicaReliability(string $accountId, string $replicaId): array|string|null
    {
        return $this->http->post("/users/current/accounts/{$accountId}/replicas/{$replicaId}/increase-reliability");
    }
}
