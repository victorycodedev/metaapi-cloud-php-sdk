<?php

namespace Victorycodedev\MetaapiCloudPhpSdk\Resources\Terminal;

final class Path
{
    public static function segment(string $value): string
    {
        return rawurlencode($value);
    }
}
