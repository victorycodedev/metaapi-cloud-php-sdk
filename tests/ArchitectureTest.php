<?php

use Victorycodedev\MetaapiCloudPhpSdk\Resources\Copyfactory\Configuration;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\Copyfactory\CopyTrade;

it('uses classes for CopyFactory resources', function (): void {
    expect((new ReflectionClass(Configuration::class))->isTrait())->toBeFalse();
    expect((new ReflectionClass(CopyTrade::class))->isTrait())->toBeFalse();
});
