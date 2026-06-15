<?php

namespace Victorycodedev\MetaapiCloudPhpSdk;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Victorycodedev\MetaapiCloudPhpSdk\Exceptions\MetaApiException;
use Victorycodedev\MetaapiCloudPhpSdk\Responses\ActionResponse;

class Http
{
    protected ClientInterface $client;

    public function __construct(protected string $token, protected string $baseUrl, ?ClientInterface $client = null)
    {
        $this->client = $client ?? new Client([
            'base_uri'    => $this->baseUrl,
            'http_errors' => false,
        ]);
    }

    public function get(string $uri, array $query = [], array $headers = []): array|string|null
    {
        return $this->request('GET', $uri, ['query' => $query, 'headers' => $headers]);
    }

    public function post(string $uri, array $payload = [], array $headers = [], array $query = []): array|string|null
    {
        return $this->request('POST', $uri, ['json' => $payload, 'headers' => $headers, 'query' => $query]);
    }

    public function postAction(string $uri, array $payload = [], array $headers = [], array $query = []): ActionResponse
    {
        return $this->requestAction('POST', $uri, ['json' => $payload, 'headers' => $headers, 'query' => $query]);
    }

    public function put(string $uri, array $payload = [], array $headers = [], array $query = []): array|string|null
    {
        return $this->request('PUT', $uri, ['json' => $payload, 'headers' => $headers, 'query' => $query]);
    }

    public function patch(string $uri, array $payload = [], array $headers = [], array $query = []): array|string|null
    {
        return $this->request('PATCH', $uri, ['json' => $payload, 'headers' => $headers, 'query' => $query]);
    }

    public function delete(string $uri, array $query = [], array $headers = [], array $payload = []): array|string|null
    {
        return $this->request('DELETE', $uri, ['query' => $query, 'headers' => $headers, 'json' => $payload]);
    }

    public function upload(string $uri, string $filePath, string $fieldName = 'file', array $headers = []): array|string|null
    {
        return $this->request('PUT', $uri, [
            'headers'   => $headers,
            'multipart' => [
                [
                    'name'     => $fieldName,
                    'contents' => fopen($filePath, 'r'),
                    'filename' => basename($filePath),
                ],
            ],
        ]);
    }

    /**
     *  Send a request to the MetaApi API.
     */
    public function request(string $verb, string $uri, array $options = []): array|string|null
    {
        $options = $this->normalizeOptions($options);
        $response = $this->client->request($verb, $this->resolveUri($uri), $options);

        if (!$this->isSuccessful($response)) {
            return $this->handleError($response);
        }

        $body = (string) $response->getBody();

        if ($body === '') {
            return null;
        }

        $decoded = json_decode($body, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return $body;
        }

        if ($response->hasHeader('Retry-After')) {
            $decoded['retryAfter'] = $response->getHeaderLine('Retry-After');
        }

        return $decoded;
    }

    public function requestAction(string $verb, string $uri, array $options = []): ActionResponse
    {
        $options = $this->normalizeOptions($options);
        $response = $this->client->request($verb, $this->resolveUri($uri), $options);

        if (!$this->isSuccessful($response)) {
            return $this->handleError($response);
        }

        return new ActionResponse(
            $this->decodeBody($response),
            $response->getStatusCode(),
            $response->getHeaders()
        );
    }

    public function isSuccessful(?ResponseInterface $response): bool
    {
        if (!$response) {
            return false;
        }

        return $response->getStatusCode() >= 200 && $response->getStatusCode() < 300;
    }

    public function handleError(ResponseInterface $response): void
    {
        $body = (string) $response->getBody();
        $decoded = json_decode($body, true);
        $error = json_last_error() === JSON_ERROR_NONE ? $decoded : $body;
        $message = is_array($error) && isset($error['message']) ? (string) $error['message'] : $body;
        $code = is_array($error) && isset($error['id']) ? (int) $error['id'] : 0;
        $statusCode = $response->getStatusCode();
        $headers = $response->getHeaders();

        throw new MetaApiException($message ?: 'MetaApi request failed', $code, $statusCode, $error, $headers);
    }

    private function normalizeOptions(array $options): array
    {
        $options['headers'] = array_merge(
            $this->defaultHeaders($options),
            $options['headers'] ?? []
        );

        foreach (['headers', 'query', 'json'] as $key) {
            if (isset($options[$key]) && $options[$key] === []) {
                unset($options[$key]);
            }
        }

        if (isset($options['query'])) {
            $options['query'] = $this->normalizeQuery($options['query']);
        }

        return $options;
    }

    private function defaultHeaders(array $options): array
    {
        $headers = [
            'auth-token' => $this->token,
            'Accept' => 'application/json',
        ];

        if (array_key_exists('json', $options)) {
            $headers['Content-Type'] = 'application/json';
        }

        return $headers;
    }

    private function resolveUri(string $uri): string
    {
        if (str_starts_with($uri, 'http://') || str_starts_with($uri, 'https://')) {
            return $uri;
        }

        return rtrim($this->baseUrl, '/') . '/' . ltrim($uri, '/');
    }

    private function normalizeQuery(array $query): array
    {
        return array_filter(
            array_map(function (mixed $value): mixed {
                if (is_bool($value)) {
                    return $value ? 'true' : 'false';
                }

                return $value;
            }, $query),
            static fn(mixed $value): bool => $value !== null
        );
    }

    private function decodeBody(ResponseInterface $response): array|string|null
    {
        $body = (string) $response->getBody();

        if ($body === '') {
            return null;
        }

        $decoded = json_decode($body, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return $body;
        }

        return $decoded;
    }
}
