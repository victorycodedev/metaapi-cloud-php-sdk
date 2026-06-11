<?php

namespace Victorycodedev\MetaapiCloudPhpSdk;

use GuzzleHttp\ClientInterface;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\AccountManagement\Account;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\AccountManagement\AccountReplica;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\AccountManagement\ProvisioningProfile;

class AccountApi
{
    public const DEFAULT_SERVER_URL = 'https://mt-provisioning-api-v1.agiliumtrade.agiliumtrade.ai';

    public Http $http;

    public string $serverUrl = self::DEFAULT_SERVER_URL;

    private Account $accounts;

    private ProvisioningProfile $provisioningProfiles;

    private AccountReplica $accountReplicas;

    public function __construct(private string $token, ?string $serverUrl = null, ?ClientInterface $client = null)
    {
        $this->serverUrl = $serverUrl ?? $this->serverUrl;
        $this->http = new Http($this->token, $this->serverUrl, $client);
        $this->accounts = new Account($this->http);
        $this->provisioningProfiles = new ProvisioningProfile($this->http);
        $this->accountReplicas = new AccountReplica($this->http);
    }

    public function accountResource(): Account
    {
        return $this->accounts;
    }

    public function provisioningProfileResource(): ProvisioningProfile
    {
        return $this->provisioningProfiles;
    }

    public function accountReplicaResource(): AccountReplica
    {
        return $this->accountReplicas;
    }

    public function readById(string $accountId): array|string|null
    {
        return $this->accounts->readById($accountId);
    }

    public function readAll(array $filters = [], ?int $apiVersion = null): array|string|null
    {
        return $this->accounts->readAll($filters, $apiVersion);
    }

    public function accounts(array $filters = [], ?int $apiVersion = null): array|string|null
    {
        return $this->accounts->accounts($filters, $apiVersion);
    }

    public function create(array $data, ?string $transactionId = null): array|string|null
    {
        return $this->accounts->create($data, $transactionId);
    }

    public function update(string $accountId, array $data): array|string|null
    {
        return $this->accounts->update($accountId, $data);
    }

    public function unDeploy(string $accountId, bool $executeForAllReplicas = true): array|string|null
    {
        return $this->accounts->unDeploy($accountId, $executeForAllReplicas);
    }

    public function deploy(string $accountId, bool $executeForAllReplicas = true): array|string|null
    {
        return $this->accounts->deploy($accountId, $executeForAllReplicas);
    }

    public function reDeploy(string $accountId, bool $executeForAllReplicas = true): array|string|null
    {
        return $this->accounts->reDeploy($accountId, $executeForAllReplicas);
    }

    public function delete(string $accountId, bool $executeForAllReplicas = false): array|string|null
    {
        return $this->accounts->delete($accountId, $executeForAllReplicas);
    }

    public function generateCodeSample(string $accountId, string $platform): array|string|null
    {
        return $this->accounts->generateCodeSample($accountId, $platform);
    }

    public function enableFeatures(string $accountId, array $data): array|string|null
    {
        return $this->accounts->enableFeatures($accountId, $data);
    }

    public function enableCopyFactoryApi(string $accountId, array $copyFactoryRoles, int $copyFactoryResourceSlots = 1): array|string|null
    {
        return $this->accounts->enableCopyFactoryApi($accountId, $copyFactoryRoles, $copyFactoryResourceSlots);
    }

    public function createConfigurationLink(string $accountId, ?int $ttlInDays = null): array|string|null
    {
        return $this->accounts->createConfigurationLink($accountId, $ttlInDays);
    }

    public function replicas(string $accountId): array|string|null
    {
        return $this->accountReplicas->replicas($accountId);
    }

    public function replica(string $accountId, string $replicaId): array|string|null
    {
        return $this->accountReplicas->replica($accountId, $replicaId);
    }

    public function createReplica(string $accountId, array $data, ?string $transactionId = null): array|string|null
    {
        return $this->accountReplicas->createReplica($accountId, $data, $transactionId);
    }

    public function updateReplica(string $accountId, string $replicaId, array $data): array|string|null
    {
        return $this->accountReplicas->updateReplica($accountId, $replicaId, $data);
    }

    public function undeployReplica(string $accountId, string $replicaId): array|string|null
    {
        return $this->accountReplicas->undeployReplica($accountId, $replicaId);
    }

    public function deployReplica(string $accountId, string $replicaId): array|string|null
    {
        return $this->accountReplicas->deployReplica($accountId, $replicaId);
    }

    public function redeployReplica(string $accountId, string $replicaId): array|string|null
    {
        return $this->accountReplicas->redeployReplica($accountId, $replicaId);
    }

    public function deleteReplica(string $accountId, string $replicaId): array|string|null
    {
        return $this->accountReplicas->deleteReplica($accountId, $replicaId);
    }

    public function generateReplicaCodeSample(string $accountId, string $replicaId, string $platform): array|string|null
    {
        return $this->accountReplicas->generateReplicaCodeSample($accountId, $replicaId, $platform);
    }

    public function increaseReplicaReliability(string $accountId, string $replicaId): array|string|null
    {
        return $this->accountReplicas->increaseReplicaReliability($accountId, $replicaId);
    }

    public function provisioningProfilesList(array $filters = [], ?int $apiVersion = null): array|string|null
    {
        return $this->provisioningProfiles->provisioningProfiles($filters, $apiVersion);
    }

    public function provisioningProfiles(array $filters = [], ?int $apiVersion = null): array|string|null
    {
        return $this->provisioningProfiles->provisioningProfiles($filters, $apiVersion);
    }

    public function provisioningProfile(string $profileId): array|string|null
    {
        return $this->provisioningProfiles->provisioningProfile($profileId);
    }

    public function createProvisioningProfile(array $data): array|string|null
    {
        return $this->provisioningProfiles->createProvisioningProfile($data);
    }

    public function uploadProvisioningProfileFile(string $profileId, string $fileName, string $filePath): array|string|null
    {
        return $this->provisioningProfiles->uploadProvisioningProfileFile($profileId, $fileName, $filePath);
    }

    public function updateProvisioningProfile(string $profileId, array $data): array|string|null
    {
        return $this->provisioningProfiles->updateProvisioningProfile($profileId, $data);
    }

    public function deleteProvisioningProfile(string $profileId): array|string|null
    {
        return $this->provisioningProfiles->deleteProvisioningProfile($profileId);
    }
}
