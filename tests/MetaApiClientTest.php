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
use Victorycodedev\MetaapiCloudPhpSdk\TerminalApi;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\AccountManagement\Account;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\AccountManagement\AccountReplica;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\AccountManagement\ProvisioningProfile;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\Copyfactory\Configuration;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\Copyfactory\CopyTrade;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\Copyfactory\History;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\Copyfactory\Logs;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\Copyfactory\Trading;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\Copyfactory\Webhooks;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\Metastats\Metrics;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\Terminal\Credits;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\Terminal\Margin;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\Terminal\MarketData;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\Terminal\State;

it('exposes focused resource objects from the root client', function (): void {
    $client = new MetaApiClient('test-token');
    $copyFactory = $client->copyFactory();

    expect($client->accounts())->toBeInstanceOf(Account::class);
    expect($client->provisioningProfiles())->toBeInstanceOf(ProvisioningProfile::class);
    expect($client->accountReplicas())->toBeInstanceOf(AccountReplica::class);
    expect($copyFactory)->toBeInstanceOf(CopyFactory::class);
    expect($copyFactory->configuration())->toBeInstanceOf(Configuration::class);
    expect($copyFactory->copyTrade())->toBeInstanceOf(CopyTrade::class);
    expect($copyFactory->webhooks())->toBeInstanceOf(Webhooks::class);
    expect($copyFactory->history())->toBeInstanceOf(History::class);
    expect($copyFactory->trading())->toBeInstanceOf(Trading::class);
    expect($copyFactory->logs())->toBeInstanceOf(Logs::class);
    expect($client->metaStats())->toBeInstanceOf(MetaStats::class);
    expect($client->metaStats()->metricResource())->toBeInstanceOf(Metrics::class);
    expect($client->terminal())->toBeInstanceOf(TerminalApi::class);
    expect($client->terminal()->state())->toBeInstanceOf(State::class);
    expect($client->terminal()->marketData())->toBeInstanceOf(MarketData::class);
    expect($client->terminal()->margin())->toBeInstanceOf(Margin::class);
    expect($client->terminal()->credits())->toBeInstanceOf(Credits::class);
});

it('shares injected clients with account resources', function (): void {
    $history = [];
    $metaapi = metaApiClientWithHistory([new Response(200, [], '[]')], $history);

    $metaapi->accounts()->accounts();

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

it('resolves terminal regional urls', function (): void {
    $httpClient = new CapturingClient();
    $client = new MetaApiClient('test-token', $httpClient);

    $client->terminal(region: 'london')->state()->accountInformation('account-id');

    expect($httpClient->lastUri)->toBe('https://mt-client-api-v1.london.agiliumtrade.ai/users/current/accounts/account-id/account-information?refreshTerminalState=false');
});

it('resolves terminal market data urls separately', function (): void {
    $httpClient = new CapturingClient();
    $client = new MetaApiClient('test-token', $httpClient);

    $client->terminal(region: 'london')->marketData()->historicalCandles('account-id', 'EURUSD', '1m');

    expect($httpClient->lastUri)->toBe('https://mt-market-data-client-api-v1.london.agiliumtrade.ai/users/current/accounts/account-id/historical-market-data/symbols/EURUSD/timeframes/1m/candles?limit=1000');
});

class CapturingClient implements ClientInterface
{
    public string $lastUri = '';

    public array $lastOptions = [];

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
        $this->lastOptions = $options;

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
