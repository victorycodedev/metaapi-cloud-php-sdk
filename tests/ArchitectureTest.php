<?php

use Victorycodedev\MetaapiCloudPhpSdk\Resources\Copyfactory\Configuration;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\Copyfactory\CopyTrade;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\Metastats\Metrics;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\Terminal\MarketData;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\Terminal\State;

it('uses classes for SDK resources', function (): void {
    expect((new ReflectionClass(Configuration::class))->isTrait())->toBeFalse();
    expect((new ReflectionClass(CopyTrade::class))->isTrait())->toBeFalse();
    expect((new ReflectionClass(Metrics::class))->isTrait())->toBeFalse();
    expect((new ReflectionClass(State::class))->isTrait())->toBeFalse();
    expect((new ReflectionClass(MarketData::class))->isTrait())->toBeFalse();
});
