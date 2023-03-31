<?php

namespace Victorycodedev\MetaapiCloudPhpSdk;

use GuzzleHttp\Client;
use Victorycodedev\MetaapiCloudPhpSdk\Apis\AccountManagement\AccountApi;
use Victorycodedev\MetaapiCloudPhpSdk\FetchApi;

class MetaApiCloud
{
    use FetchApi;
    use AccountApi;

    /**
     * @var string
     */
    private $token;

    protected Client $client;


    public function __construct(string $token, Client $client = null)
    {
        $this->token = $token;

        $this->client = $client ?? new Client([
            'http_errors' => false,
            'headers' => [
                'auth-token' => $this->token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);
    }
}
