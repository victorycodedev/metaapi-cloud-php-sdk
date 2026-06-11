<?php

use Victorycodedev\MetaapiCloudPhpSdk\Resources\Copyfactory\Configuration;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\Copyfactory\CopyTrade;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\Metastats\Metrics;

it('uses classes for SDK resources', function (): void {
    expect((new ReflectionClass(Configuration::class))->isTrait())->toBeFalse();
    expect((new ReflectionClass(CopyTrade::class))->isTrait())->toBeFalse();
    expect((new ReflectionClass(Metrics::class))->isTrait())->toBeFalse();
});
