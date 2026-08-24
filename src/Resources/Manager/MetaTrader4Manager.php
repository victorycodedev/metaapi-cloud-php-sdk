<?php

namespace Victorycodedev\MetaapiCloudPhpSdk\Resources\Manager;

use Victorycodedev\MetaapiCloudPhpSdk\Http;

class MetaTrader4Manager extends MetaTraderManager
{
    public function __construct(Http $http)
    {
        parent::__construct($http, 4);
    }

    public function changePassword(string $profileId, string|int $login, string $password, string $type = 'master'): array|string|null
    {
        return $this->http->put($this->accountPath($profileId, $login) . '/password', [
            'password' => $password,
            'type' => $type,
        ]);
    }
}
