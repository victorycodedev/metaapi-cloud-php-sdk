<?php

namespace Victorycodedev\MetaapiCloudPhpSdk;

use GuzzleHttp\ClientInterface;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\AccountManagement\Account;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\AccountManagement\AccountReplica;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\AccountManagement\ProvisioningProfile;

class MetaApiClient
{
    public const DEFAULT_ACCOUNT_MANAGEMENT_URL = 'https://mt-provisioning-api-v1.agiliumtrade.agiliumtrade.ai';

    public const DEFAULT_REGION = 'new-york';

    private const REGIONAL_SERVICE_URLS = [
        'copyFactory' => 'https://copyfactory-api-v1.%s.agiliumtrade.ai',
        'metaStats'   => 'https://metastats-api-v1.%s.agiliumtrade.ai',
    ];

    private Http $accountHttp;

    public function __construct(
        private readonly string $token,
        private readonly ?ClientInterface $client = null,
        private readonly array $baseUrls = []
    ) {
        $this->accountHttp = new Http(
            $this->token,
            $this->baseUrl('accountManagement', self::DEFAULT_ACCOUNT_MANAGEMENT_URL),
            $this->client
        );
    }

    public function accounts(): Account
    {
        return new Account($this->accountHttp);
    }

    public function provisioningProfiles(): ProvisioningProfile
    {
        return new ProvisioningProfile($this->accountHttp);
    }

    public function accountReplicas(): AccountReplica
    {
        return new AccountReplica($this->accountHttp);
    }

    public function accountApi(): AccountApi
    {
        return new AccountApi(
            $this->token,
            $this->baseUrl('accountManagement', self::DEFAULT_ACCOUNT_MANAGEMENT_URL),
            $this->client
        );
    }

    public function copyFactory(?string $region = null, ?string $serverUrl = null): CopyFactory
    {
        return new CopyFactory(
            $this->token,
            $serverUrl ?? $this->regionalBaseUrl('copyFactory', $region),
            $this->client
        );
    }

    public function metaStats(?string $region = null, ?string $serverUrl = null): MetaStats
    {
        return new MetaStats(
            $this->token,
            $serverUrl ?? $this->regionalBaseUrl('metaStats', $region),
            $this->client
        );
    }

    private function baseUrl(string $service, string $default): string
    {
        return $this->baseUrls[$service] ?? $default;
    }

    private function regionalBaseUrl(string $service, ?string $region): string
    {
        if (isset($this->baseUrls[$service])) {
            return $this->baseUrls[$service];
        }

        $region = $this->normalizeRegion($region ?? self::DEFAULT_REGION);

        return sprintf(self::REGIONAL_SERVICE_URLS[$service], $region);
    }

    private function normalizeRegion(string $region): string
    {
        return str_replace('_', '-', strtolower(trim($region)));
    }
}