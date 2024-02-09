<?php

namespace GenDiff\Utils;

function formatToString(mixed $value): string
{
    return match (gettype($value)) {
        'string' => $value,
        'integer' => (string) $value,
        'double' => (string) $value,
        'boolean' => var_export($value, true),
        'NULL' => 'null'
    };
}
