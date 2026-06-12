<?php

namespace Victorycodedev\MetaapiCloudPhpSdk\Resources\Copyfactory;

use Victorycodedev\MetaapiCloudPhpSdk\Http;
use Victorycodedev\MetaapiCloudPhpSdk\Exceptions\MetaApiException;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\AccountManagement\Account;

class CopyTrade
{
    public function __construct(
        private readonly Http $http,
        private readonly Configuration $configuration,
        private readonly Account $accounts
    ) {}

    /*
    *   Configures CopyFactory to copy a provider strategy into a subscriber account.
    */
    public function configureCopyTrading(
        string $providerAccountId,
        string $subscriberAccountId,
        ?string $strategyId = null,
        array $strategy = [],
        array $subscription = [],
        array $subscriber = [],
        bool $validateAccountRoles = true,
        bool $reuseExistingStrategy = false,
        ?array $providerAccount = null,
        ?array $subscriberAccount = null
    ): array {
        if ($validateAccountRoles || $reuseExistingStrategy) {
            $providerAccount ??= $this->accounts->readById($providerAccountId);
        }

        if ($validateAccountRoles) {
            $subscriberAccount ??= $this->accounts->readById($subscriberAccountId);
            $this->assertAccountRole($providerAccount, 'PROVIDER', $providerAccountId);
            $this->assertAccountRole($subscriberAccount, 'SUBSCRIBER', $subscriberAccountId);
        }

        if ($strategyId === null && $reuseExistingStrategy) {
            $strategyId = $this->findStrategyIdForAccount($providerAccount['_id'] ?? $providerAccountId);
        }

        $strategyId ??= $this->configuration->generateStrategyId()['id'];
        $providerMetaApiAccountId = $providerAccount['_id'] ?? $providerAccountId;
        $subscriberMetaApiAccountId = $subscriberAccount['_id'] ?? $subscriberAccountId;

        $strategyPayload = array_replace([
            'name'        => 'CopyFactory strategy',
            'description' => 'CopyFactory strategy configured by SDK',
            'accountId'   => $providerMetaApiAccountId,
        ], $strategy);

        $strategyPayload['accountId'] = $strategyPayload['accountId'] ?? $providerMetaApiAccountId;

        $subscriptionPayload = array_replace([
            'strategyId' => $strategyId,
            'multiplier' => 1,
        ], $subscription);

        $subscriptionPayload['strategyId'] = $subscriptionPayload['strategyId'] ?? $strategyId;

        $subscriberPayload = array_replace([
            'name'          => 'CopyFactory subscriber',
            'subscriptions' => [$subscriptionPayload],
        ], $subscriber);

        $subscriberPayload['subscriptions'] = $subscriberPayload['subscriptions'] ?? [$subscriptionPayload];

        $this->configuration->updateStrategy($strategyId, $strategyPayload);
        $this->configuration->updateSubscriber($subscriberMetaApiAccountId, $subscriberPayload);

        return [
            'message'             => 'Copy trading configured successfully',
            'strategyId'          => $strategyId,
            'providerAccountId'   => $providerMetaApiAccountId,
            'subscriberAccountId' => $subscriberMetaApiAccountId,
        ];
    }

    public function copy(string $providerAccount, string $subscriberAccount, ?string $strategyId = null): array|string
    {
        return $this->configureCopyTrading($providerAccount, $subscriberAccount, $strategyId);
    }

    private function assertAccountRole(array $account, string $role, string $accountId): void
    {
        if (in_array($role, $account['copyFactoryRoles'] ?? [], true)) {
            return;
        }

        throw new MetaApiException(
            "Account {$accountId} is not a {$role} account. Please specify {$role} copyFactoryRoles value in your MetaApi account in order to use it in CopyFactory API",
            response: [
                'error' => 'ValidationError',
                'message' => "Account {$accountId} is not a {$role} account.",
                'details' => [
                    'accountId' => $accountId,
                    'requiredRole' => $role,
                ],
            ]
        );
    }

    private function findStrategyIdForAccount(string $accountId): ?string
    {
        $strategies = $this->configuration->strategies();

        foreach ($strategies as $strategy) {
            if (($strategy['accountId'] ?? null) === $accountId) {
                return $strategy['_id'] ?? $strategy['id'] ?? null;
            }
        }

        return null;
    }
}
