<?php

namespace Victorycodedev\MetaapiCloudPhpSdk\Resources\Manager;

use Victorycodedev\MetaapiCloudPhpSdk\Http;

class MetaTrader5Manager extends MetaTraderManager
{
    public function __construct(Http $http)
    {
        parent::__construct($http, 5);
    }

    public function setAccountTrading(string $profileId, string|int $login, bool $trading): array|string|null
    {
        return $this->http->put($this->accountPath($profileId, $login) . '/trading-enabled', [
            'trading' => $trading,
        ]);
    }
}
