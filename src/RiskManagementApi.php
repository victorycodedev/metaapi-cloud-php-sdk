<?php

namespace Victorycodedev\MetaapiCloudPhpSdk;

use GuzzleHttp\ClientInterface;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\RiskManagement\Trackers;

class RiskManagementApi
{
    private Http $http;

    private Trackers $trackers;

    public function __construct(
        private readonly string $token,
        private readonly string $serverUrl,
        ?ClientInterface $client = null
    ) {
        $this->http = new Http($this->token, $this->serverUrl, $client);
        $this->trackers = new Trackers($this->http);
    }

    public function trackers(): Trackers
    {
        return $this->trackers;
    }
}