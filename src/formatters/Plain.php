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

function flatten($lines)
{
    $flattenLine = fn (array $lines) => implode("\n", array_filter($lines, fn ($line) => $line !== ''));
    $flattenLines = array_map(function ($line) use ($flattenLine) {
        return is_array($line) ? $flattenLine($line) : $line;
    }, $lines);

    return $flattenLine($flattenLines);
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

    return flatten($nestedLines);
}
