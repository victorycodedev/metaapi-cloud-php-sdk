# Account Replicas

```php
$replicas = $metaapi->accountReplicas();
```

```php
$response = $replicas->createReplica('primary-account-id', [
    'magic' => 123456,
    'region' => 'london',
]);

if ($response->shouldRetry()) {
    echo "Replica creation accepted. Retry after: " . ($response->retryAfter() ?? 'not specified');
}
```

A transaction ID is auto-generated. Pass your own `transactionId` to retry an accepted request.

Available methods:

- `replicas(string $accountId)`
- `replica(string $accountId, string $replicaId)`
- `createReplica(string $accountId, array $data, ?string $transactionId = null)` returns `ActionResponse`
- `updateReplica(string $accountId, string $replicaId, array $data)`
- `undeployReplica(string $accountId, string $replicaId)`
- `deployReplica(string $accountId, string $replicaId)`
- `redeployReplica(string $accountId, string $replicaId)`
- `deleteReplica(string $accountId, string $replicaId)`
- `generateReplicaCodeSample(string $accountId, string $replicaId, string $platform)`
- `increaseReplicaReliability(string $accountId, string $replicaId)`
