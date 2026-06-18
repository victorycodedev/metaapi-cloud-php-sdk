<?php

namespace Victorycodedev\MetaapiCloudPhpSdk\Resources\AccountManagement;

use Victorycodedev\MetaapiCloudPhpSdk\Http;

class ExpertAdvisor
{
    public function __construct(private readonly Http $http) {}

    public function expertAdvisors(string $accountId): array|string|null
    {
        return $this->http->get("/users/current/accounts/{$accountId}/expert-advisors");
    }

    public function expertAdvisor(string $accountId, string $expertId): array|string|null
    {
        return $this->http->get("/users/current/accounts/{$accountId}/expert-advisors/{$expertId}");
    }

    public function updateExpertAdvisor(string $accountId, string $expertId, array $data): array|string|null
    {
        return $this->http->put("/users/current/accounts/{$accountId}/expert-advisors/{$expertId}", $data);
    }

    public function uploadExpertAdvisorFile(string $accountId, string $expertId, string $filePath): array|string|null
    {
        return $this->http->upload(
            "/users/current/accounts/{$accountId}/expert-advisors/{$expertId}/file",
            $filePath
        );
    }

    public function deleteExpertAdvisor(string $accountId, string $expertId): array|string|null
    {
        return $this->http->delete("/users/current/accounts/{$accountId}/expert-advisors/{$expertId}");
    }
}
