<?php

namespace Victorycodedev\MetaapiCloudPhpSdk\Resources\RiskManagement;

use Victorycodedev\MetaapiCloudPhpSdk\Http;

class Trackers
{
    public function __construct(private readonly Http $http) {}

    public function create(string $accountId, array $data, ?int $apiVersion = 1): array|string|null
    {
        return $this->http->post($this->accountTrackersPath($accountId), $data, $this->headers($apiVersion));
    }

    public function events(array $filters = []): array|string|null
    {
        return $this->http->get('/users/current/tracker-events/by-broker-time', $filters);
    }

    public function eventStream(array $filters = [], ?int $apiVersion = 1): array|string|null
    {
        return $this->http->get('/users/current/tracker-events/stream', $filters, $this->headers($apiVersion));
    }

    public function statistics(string $accountId, string $trackerId, array $filters = [], ?int $apiVersion = 1): array|string|null
    {
        return $this->http->get($this->trackerPath($accountId, $trackerId) . '/statistics', $filters, $this->headers($apiVersion));
    }

    public function tracker(string $accountId, string $trackerId, ?int $apiVersion = 1): array|string|null
    {
        return $this->http->get($this->trackerPath($accountId, $trackerId), [], $this->headers($apiVersion));
    }

    public function trackerByName(string $accountId, string $name, ?int $apiVersion = 1): array|string|null
    {
        return $this->http->get($this->accountTrackersPath($accountId) . '/name/' . Path::segment($name), [], $this->headers($apiVersion));
    }

    public function trackers(string $accountId, ?int $apiVersion = 1): array|string|null
    {
        return $this->http->get($this->accountTrackersPath($accountId), [], $this->headers($apiVersion));
    }

    public function equityChart(string $accountId, array $filters = [], ?int $apiVersion = 1): array|string|null
    {
        return $this->http->get('/users/current/accounts/' . Path::segment($accountId) . '/equity-chart', $filters, $this->headers($apiVersion));
    }

    public function remove(string $accountId, string $trackerId): array|string|null
    {
        return $this->http->delete($this->trackerPath($accountId, $trackerId));
    }

    public function update(string $accountId, string $trackerId, array $data): array|string|null
    {
        return $this->http->put($this->trackerPath($accountId, $trackerId), $data);
    }

    private function accountTrackersPath(string $accountId): string
    {
        return '/users/current/accounts/' . Path::segment($accountId) . '/trackers';
    }

    private function trackerPath(string $accountId, string $trackerId): string
    {
        return $this->accountTrackersPath($accountId) . '/' . Path::segment($trackerId);
    }

    private function headers(?int $apiVersion): array
    {
        if ($apiVersion === null) {
            return [];
        }

        return ['api-version' => (string) $apiVersion];
    }
}
