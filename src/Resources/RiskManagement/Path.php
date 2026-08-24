<?php

namespace Victorycodedev\MetaapiCloudPhpSdk\Resources\RiskManagement;

final class Path
{
    public static function segment(string|int $value): string
    {
        return rawurlencode((string) $value);
    }
}
