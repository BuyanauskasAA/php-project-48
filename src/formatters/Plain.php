<?php

namespace GenDiff\Formatters\Plain;

function stringify(mixed $value): string
{
    return match (gettype($value)) {
        'array' => '[complex value]',
        'string' => "'$value'",
        'boolean' => $value ? 'true' : 'false',
        'NULL' => 'null',
        default => "{$value}"
    };
}

function makePlain($diff, string $path = '')
{
    $nestedLines = array_map(function ($node) use ($path) {
        $type = $node['type'];
        $key = $node['key'];
        $path = $path === '' ? "{$key}" : "{$path}.{$key}";

        switch ($type) {
            case 'added':
                $value = stringify($node['value']);
                return "Property '{$path}' was added with value: $value";
            case 'deleted':
                return "Property '{$path}' was removed";
            case 'changed':
                $oldValue = stringify($node['oldValue']);
                $newValue = stringify($node['newValue']);
                return "Property '{$path}' was updated. From {$oldValue} to {$newValue}";
            case 'unchanged':
                return '';
            case 'nested':
                return makePlain($node['children'], $path);
        }
    }, $diff);

    $flattenLines = array_map(function ($line) {
        if (is_array($line)) {
            return implode("\n", array_filter($line, fn ($l) => $l !== ''));
        } else {
            return $line;
        }
    }, $nestedLines);

    return implode("\n", array_filter($flattenLines, fn ($l) => $l !== ''));
}
