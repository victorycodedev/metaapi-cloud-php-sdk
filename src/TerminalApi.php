<?php

namespace Victorycodedev\MetaapiCloudPhpSdk;

use GuzzleHttp\ClientInterface;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\Terminal\Credits;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\Terminal\History;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\Terminal\Margin;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\Terminal\MarketData;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\Terminal\State;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\Terminal\Trading;

class TerminalApi
{
    private Http $http;

    private Http $marketDataHttp;

    private State $state;

    private History $history;

    private MarketData $marketData;

    private Margin $margin;

    private Trading $trading;

    private Credits $credits;

    public function __construct(
        private readonly string $token,
        private readonly string $serverUrl,
        private readonly string $marketDataServerUrl,
        ?ClientInterface $client = null
    ) {
        $this->http = new Http($this->token, $this->serverUrl, $client);
        $this->marketDataHttp = new Http($this->token, $this->marketDataServerUrl, $client);
        $this->state = new State($this->http);
        $this->history = new History($this->http);
        $this->marketData = new MarketData($this->http, $this->marketDataHttp);
        $this->margin = new Margin($this->http);
        $this->trading = new Trading($this->http);
        $this->credits = new Credits($this->http);
    }

    public function state(): State
    {
        return $this->state;
    }

    public function history(): History
    {
        return $this->history;
    }

    public function marketData(): MarketData
    {
        return $this->marketData;
    }

    public function margin(): Margin
    {
        return $this->margin;
    }

    public function trading(): Trading
    {
        return $this->trading;
    }

    public function credits(): Credits
    {
        return $this->credits;
    }
}
