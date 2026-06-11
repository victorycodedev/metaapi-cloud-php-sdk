<?php

namespace Victorycodedev\MetaapiCloudPhpSdk\Resources\AccountManagement;

use Victorycodedev\MetaapiCloudPhpSdk\Http;

class ProvisioningProfile
{
    public function __construct(private readonly Http $http)
    {
    }

    public function provisioningProfiles(array $filters = [], ?int $apiVersion = null): array|string|null
    {
        return $this->http->get(
            '/users/current/provisioning-profiles',
            $filters,
            $apiVersion ? ['api-version' => (string) $apiVersion] : []
        );
    }

    public function provisioningProfile(string $profileId): array|string|null
    {
        return $this->http->get("/users/current/provisioning-profiles/{$profileId}");
    }

    public function createProvisioningProfile(array $data): array|string|null
    {
        return $this->http->post('/users/current/provisioning-profiles', $data);
    }

    public function uploadProvisioningProfileFile(string $profileId, string $fileName, string $filePath): array|string|null
    {
        return $this->http->upload("/users/current/provisioning-profiles/{$profileId}/{$fileName}", $filePath);
    }

    public function updateProvisioningProfile(string $profileId, array $data): array|string|null
    {
        return $this->http->put("/users/current/provisioning-profiles/{$profileId}", $data);
    }

    public function deleteProvisioningProfile(string $profileId): array|string|null
    {
        return $this->http->delete("/users/current/provisioning-profiles/{$profileId}");
    }
}
