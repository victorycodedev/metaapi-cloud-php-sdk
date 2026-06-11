<?php

namespace Victorycodedev\MetaapiCloudPhpSdk\Resources\Copyfactory;

use Victorycodedev\MetaapiCloudPhpSdk\AccountApi;
use Victorycodedev\MetaapiCloudPhpSdk\Http;

class CopyTrade
{
    public function __construct(
        private readonly Http $http,
        private readonly Configuration $configuration,
        private readonly string $token
    ) {
    }

    /*
    *   Create actual copy trade in metaapi.cloud
    */
    public function copy(string $providerAccount, string $subscriberAccount, ?string $strategyId = null): array|string
    {
        $account = new AccountApi($this->token);

        $masterMetaapiAccount = $account->readById($providerAccount);
        $slaveMetaapiAccount = $account->readById($subscriberAccount);

        if (!in_array('PROVIDER', $masterMetaapiAccount['copyFactoryRoles'] ?? [], true)) {
            throw new \InvalidArgumentException("Account {$providerAccount} is not a provider account. Please specify PROVIDER copyFactoryRoles value in your MetaApi account in order to use it in CopyFactory API");
        }

        if (!in_array('SUBSCRIBER', $slaveMetaapiAccount['copyFactoryRoles'] ?? [], true)) {
            throw new \InvalidArgumentException("Account {$subscriberAccount} is not a subscriber account. Please specify SUBSCRIBER copyFactoryRoles value in your MetaApi account in order to use it in CopyFactory API");
        }

        // get strategy ID
        if (empty($strategyId)) {
            $strategies = $this->configuration->strategies();
            $strategy = [];

            foreach ($strategies as $value) {
                if (($value['accountId'] ?? null) === $masterMetaapiAccount['_id']) {
                    $strategy = $value;
                    break;
                }
            }

            if (!empty($strategy)) {
                $strategyId = $strategy['_id'];
            } else {
                $strategyId = $this->configuration->generateStrategyId()['id'];
            }
        }

        // create a strategy being copied
        $this->http->put("/users/current/configuration/strategies/{$strategyId}", [
            'name'        => 'Test strategy',
            'description' => 'Some useful description about your strategy',
            'accountId'   => $masterMetaapiAccount['_id'],
        ]);

        // create subscriber
        $this->configuration->updateSubscriber($slaveMetaapiAccount['_id'], [
            'name'          => 'Copy Trade Subscriber',
            'subscriptions' => [
                [
                    'strategyId' => $strategyId,
                    'multiplier' => 1,
                ],
            ],
        ]);

        return  ['message' => 'Copy trade created successfully'];
    }
}
