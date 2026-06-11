<?php

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Promise\Create;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;
use Victorycodedev\MetaapiCloudPhpSdk\CopyFactory;
use Victorycodedev\MetaapiCloudPhpSdk\MetaApiClient;
use Victorycodedev\MetaapiCloudPhpSdk\MetaStats;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\AccountManagement\Account;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\AccountManagement\AccountReplica;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\AccountManagement\ProvisioningProfile;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\Copyfactory\Configuration;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\Copyfactory\CopyTrade;

it('exposes focused resource objects from the root client', function (): void {
    $client = new MetaApiClient('test-token');
    $copyFactory = $client->copyFactory();

    expect($client->accounts())->toBeInstanceOf(Account::class);
    expect($client->provisioningProfiles())->toBeInstanceOf(ProvisioningProfile::class);
    expect($client->accountReplicas())->toBeInstanceOf(AccountReplica::class);
    expect($copyFactory)->toBeInstanceOf(CopyFactory::class);
    expect($copyFactory->configuration())->toBeInstanceOf(Configuration::class);
    expect($copyFactory->copyTrade())->toBeInstanceOf(CopyTrade::class);
    expect($client->metaStats())->toBeInstanceOf(MetaStats::class);
});

it('shares injected clients with account resources', function (): void {
    $history = [];
    $api = accountApiWithHistory([new Response(200, [], '[]')], $history);

    $api->accountResource()->accounts();

    expect($history[0]['request'])->toHaveSentRequest('GET', '/users/current/accounts');
});

it('resolves CopyFactory regional urls', function (): void {
    $httpClient = new CapturingClient();
    $client = new MetaApiClient('test-token', $httpClient);

    $client->copyFactory(region: 'london')->strategies();

    expect($httpClient->lastUri)->toBe('https://copyfactory-api-v1.london.agiliumtrade.ai/users/current/configuration/strategies?includeRemoved=false&limit=1000&offset=0');
});

it('normalizes regional names', function (): void {
    $httpClient = new CapturingClient();
    $client = new MetaApiClient('test-token', $httpClient);

    $client->copyFactory(region: 'new_york')->strategies();

    expect($httpClient->lastUri)->toBe('https://copyfactory-api-v1.new-york.agiliumtrade.ai/users/current/configuration/strategies?includeRemoved=false&limit=1000&offset=0');
});

it('allows custom CopyFactory urls for private regions', function (): void {
    $httpClient = new CapturingClient();
    $client = new MetaApiClient('test-token', $httpClient);

    $client->copyFactory(serverUrl: 'https://copyfactory-api-v1.tokyo.example.com')->strategies();

    expect($httpClient->lastUri)->toBe('https://copyfactory-api-v1.tokyo.example.com/users/current/configuration/strategies?includeRemoved=false&limit=1000&offset=0');
});

it('resolves MetaStats regional urls', function (): void {
    $httpClient = new CapturingClient();
    $client = new MetaApiClient('test-token', $httpClient);

    $client->metaStats(region: 'london')->openTrades('account-id');

    expect($httpClient->lastUri)->toBe('https://metastats-api-v1.london.agiliumtrade.ai/users/current/accounts/account-id/open-trades');
});

class CapturingClient implements ClientInterface
{
    public string $lastUri = '';

    public function send(RequestInterface $request, array $options = []): ResponseInterface
    {
        $this->lastUri = (string) $request->getUri();

        return new Response(200, [], '[]');
    }

    public function sendAsync(RequestInterface $request, array $options = []): PromiseInterface
    {
        return Create::promiseFor($this->send($request, $options));
    }

    public function request($method, $uri = '', array $options = []): ResponseInterface
    {
        $query = isset($options['query']) ? '?' . http_build_query($options['query']) : '';
        $this->lastUri = (string) $uri . $query;

        return new Response(200, [], '[]');
    }

    public function requestAsync($method, $uri = '', array $options = []): PromiseInterface
    {
        return Create::promiseFor($this->request($method, $uri, $options));
    }

    public function getConfig(?string $option = null): mixed
    {
        return null;
    }
}
