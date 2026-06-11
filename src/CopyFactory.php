<?php

namespace Victorycodedev\MetaapiCloudPhpSdk;

use GuzzleHttp\ClientInterface;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\Copyfactory\Configuration;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\Copyfactory\CopyTrade;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\Copyfactory\History;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\Copyfactory\Logs;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\Copyfactory\Trading;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\Copyfactory\Webhooks;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\AccountManagement\Account;

class CopyFactory
{
    public Http $http;

    public string $serverUrl = 'https://copyfactory-api-v1.new-york.agiliumtrade.ai';

    private Configuration $configuration;

    private CopyTrade $copyTrade;

    private Webhooks $webhooks;

    private History $history;

    private Trading $trading;

    private Logs $logs;

    public function __construct(private string $token, ?string $serverUrl = null, ?ClientInterface $client = null)
    {
        $this->serverUrl = $serverUrl ?? $this->serverUrl;
        $this->http = new Http($this->token, $this->serverUrl, $client);
        $this->configuration = new Configuration($this->http);
        $accounts = new Account(new Http($this->token, AccountApi::DEFAULT_SERVER_URL, $client));
        $this->copyTrade = new CopyTrade($this->http, $this->configuration, $accounts);
        $this->webhooks = new Webhooks($this->http);
        $this->history = new History($this->http);
        $this->trading = new Trading($this->http);
        $this->logs = new Logs($this->http);
    }

    public function configuration(): Configuration
    {
        return $this->configuration;
    }

    public function copyTrade(): CopyTrade
    {
        return $this->copyTrade;
    }

    public function webhooks(): Webhooks
    {
        return $this->webhooks;
    }

    public function history(): History
    {
        return $this->history;
    }

    public function trading(): Trading
    {
        return $this->trading;
    }

    public function logs(): Logs
    {
        return $this->logs;
    }

    public function generateStrategyId(): array|string|null
    {
        return $this->configuration->generateStrategyId();
    }

    public function strategies(bool $includeRemoved = false, int $limit = 1000, int $offset = 0): array|string|null
    {
        return $this->configuration->strategies($includeRemoved, $limit, $offset);
    }

    public function strategy(string $strategyId): array|string|null
    {
        return $this->configuration->strategy($strategyId);
    }

    public function portfolioStrategies(bool $includeRemoved = false, int $limit = 1000, int $offset = 0, ?int $apiVersion = null): array|string|null
    {
        return $this->configuration->portfolioStrategies($includeRemoved, $limit, $offset, $apiVersion);
    }

    public function portfolioStrategy(string $portfolioId): array|string|null
    {
        return $this->configuration->portfolioStrategy($portfolioId);
    }

    public function updatePortfolioStrategy(string $portfolioId, array $data): array|string|null
    {
        return $this->configuration->updatePortfolioStrategy($portfolioId, $data);
    }

    public function removePortfolioStrategy(string $portfolioId, array $closeInstructions = []): array|string|null
    {
        return $this->configuration->removePortfolioStrategy($portfolioId, $closeInstructions);
    }

    public function removePortfolioStrategyMember(string $portfolioId, string $strategyId, array $closeInstructions = []): array|string|null
    {
        return $this->configuration->removePortfolioStrategyMember($portfolioId, $strategyId, $closeInstructions);
    }

    public function updateStrategy(string $strategyId, array $data): array|string|null
    {
        return $this->configuration->updateStrategy($strategyId, $data);
    }

    public function removeStrategy(string $strategyId): array|string|null
    {
        return $this->configuration->removeStrategy($strategyId);
    }

    public function subscribers(bool $includeRemoved = false, int $limit = 1000, int $offset = 0): array|string|null
    {
        return $this->configuration->subscribers($includeRemoved, $limit, $offset);
    }

    public function subscriber(string $subscriberId): array|string|null
    {
        return $this->configuration->subscriber($subscriberId);
    }

    public function updateSubscriber(string $subscriberId, array $data): array|string|null
    {
        return $this->configuration->updateSubscriber($subscriberId, $data);
    }

    public function removeSubscriber(string $subscriberId): array|string|null
    {
        return $this->configuration->removeSubscriber($subscriberId);
    }

    public function deleteSubscription(string $subscriberId, string $strategyId): array|string|null
    {
        return $this->configuration->deleteSubscription($subscriberId, $strategyId);
    }

    public function getWebhooks(string $strategyId, array $query = []): array|string|null
    {
        return $this->webhooks->list($strategyId, $query);
    }

    public function createWebhook(string $strategyId, array $data): array|string|null
    {
        return $this->webhooks->create($strategyId, $data);
    }

    public function updateWebhook(string $strategyId, string $webhookId, array $data): array|string|null
    {
        return $this->webhooks->update($strategyId, $webhookId, $data);
    }

    public function removeWebhook(string $strategyId, string $webhookId): array|string|null
    {
        return $this->webhooks->remove($strategyId, $webhookId);
    }

    public function providedTransactions(array $query): array|string|null
    {
        return $this->history->providedTransactions($query);
    }

    public function subscriptionTransactions(array $query): array|string|null
    {
        return $this->history->subscriptionTransactions($query);
    }

    public function strategyTransactionsStream(string $strategyId, array $query = []): array|string|null
    {
        return $this->history->strategyTransactionsStream($strategyId, $query);
    }

    public function subscriberTransactionsStream(string $subscriberId, array $query = []): array|string|null
    {
        return $this->history->subscriberTransactionsStream($subscriberId, $query);
    }

    public function signals(string $subscriberId): array|string|null
    {
        return $this->trading->signals($subscriberId);
    }

    public function externalSignals(string $strategyId): array|string|null
    {
        return $this->trading->externalSignals($strategyId);
    }

    public function updateExternalSignal(string $strategyId, string $signalId, array $data): array|string|null
    {
        return $this->trading->updateExternalSignal($strategyId, $signalId, $data);
    }

    public function removeExternalSignal(string $strategyId, string $signalId, array $data): array|string|null
    {
        return $this->trading->removeExternalSignal($strategyId, $signalId, $data);
    }

    public function stopouts(string $subscriberId): array|string|null
    {
        return $this->trading->stopouts($subscriberId);
    }

    public function resetSubscriptionStopouts(string $subscriberId, string $strategyId, string $reason): array|string|null
    {
        return $this->trading->resetSubscriptionStopouts($subscriberId, $strategyId, $reason);
    }

    public function resetSubscriberStopouts(string $subscriberId, string $reason): array|string|null
    {
        return $this->trading->resetSubscriberStopouts($subscriberId, $reason);
    }

    public function stopoutsStream(array $query = []): array|string|null
    {
        return $this->trading->stopoutsStream($query);
    }

    public function resynchronize(string $subscriberId, array $query = []): array|string|null
    {
        return $this->trading->resynchronize($subscriberId, $query);
    }

    public function userLog(string $subscriberId, array $query = []): array|string|null
    {
        return $this->logs->userLog($subscriberId, $query);
    }

    public function userLogStream(string $subscriberId, array $query = []): array|string|null
    {
        return $this->logs->userLogStream($subscriberId, $query);
    }

    public function strategyLog(string $strategyId, array $query = []): array|string|null
    {
        return $this->logs->strategyLog($strategyId, $query);
    }

    public function strategyLogStream(string $strategyId, array $query = []): array|string|null
    {
        return $this->logs->strategyLogStream($strategyId, $query);
    }

    public function copy(string $providerAccount, string $subscriberAccount, ?string $strategyId = null): array|string
    {
        return $this->copyTrade->copy($providerAccount, $subscriberAccount, $strategyId);
    }

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
        return $this->copyTrade->configureCopyTrading(
            $providerAccountId,
            $subscriberAccountId,
            $strategyId,
            $strategy,
            $subscription,
            $subscriber,
            $validateAccountRoles,
            $reuseExistingStrategy,
            $providerAccount,
            $subscriberAccount
        );
    }
}
