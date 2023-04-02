<?php

namespace Victorycodedev\MetaapiCloudPhpSdk\Resources\Copyfactory;

trait CopyTrade
{
    use Configuration;
    public string $acntUrl = "https://mt-provisioning-api-v1.agiliumtrade.agiliumtrade.a";
    /*
    *   Create actual copy trade in metaapi.cloud
    */
    public function copy(string $providerAccount, string $subscriberAccount): array|string
    {
        $error = [];

        try {
            $masterMetaapiAccount = $this->http->get("{$this->acntUrl}/users/current/accounts/{$providerAccount}");
            $slaveMetaapiAccount = $this->http->get("{$this->acntUrl}/users/current/accounts/{$subscriberAccount}");

            if (!in_array('PROVIDER', $masterMetaapiAccount['copyFactoryRoles'])) {
                $error['message'] = "Account {$providerAccount} is not a provider account. Please specify PROVIDER copyFactoryRoles value in your MetaApi account in order to use it in CopyFactory API";
                throw new \Exception((string) $error);
            }

            if (!in_array('SUBSCRIBER', $slaveMetaapiAccount['copyFactoryRoles'])) {
                $error['message'] = "Account {$subscriberAccount} is not a subscriber account. Please specify SUBSCRIBER copyFactoryRoles value in your MetaApi account in ' +
                'order to use it in CopyFactory API";
                throw new \Exception((string) $error);
            }

            // get strategy ID
            $strategies = $this->strategies();
            $strategy = [];

            foreach ($strategies as $value) {
                if ($value['accountId'] == $masterMetaapiAccount['_id']) {
                    $strategy = $value;
                    break;
                }
            }

            if (!empty($strategy)) {
                $strategyId = $strategy['_id'];
            } else {
                $strategyId = $this->generateStrategyId();
            }

            // create a strategy being copied
            $this->http->put("{$this->baseUrl}/users/current/configuration/strategies/{$strategyId}", [
                "name" => "Test strategy",
                "description" => "Some useful description about your strategy",
                "accountId" => $masterMetaapiAccount['_id']
            ]);

            // create subscriber
            $this->updateSubscriber($slaveMetaapiAccount['_id'], [
                'strategyId' => $strategyId,
                'multiplier' => 1
            ]);

            return (string) ['message' => "Copy trade created successfully"];
        } catch (\Throwable $th) {
            throw new \Exception((string) $th->getMessage());
        }
    }
}
