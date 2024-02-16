<?php

namespace Differ\Formatters\Plain;

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

function flatten(array $lines): string
{
    $flattenLine = fn (array $lines) => implode("\n", array_filter($lines, fn ($line) => $line !== ''));
    $flattenLines = array_map(function ($line) use ($flattenLine) {
        return is_array($line) ? $flattenLine($line) : $line;
    }, $lines);

    return $flattenLine($flattenLines);
}

function makePlain(array $diff, string $path = ''): string
{
    $nestedLines = array_map(function ($node) use ($path) {
        $newPath = $path === '' ? "{$node['key']}" : "{$path}.{$node['key']}";

        switch ($node['type']) {
            case 'added':
                return "Property '{$newPath}' was added with value: " . stringify($node['value']);
            case 'deleted':
                return "Property '{$newPath}' was removed";
            case 'changed':
                $oldValue = stringify($node['oldValue']);
                $newValue = stringify($node['newValue']);
                return "Property '{$newPath}' was updated. From {$oldValue} to {$newValue}";
            case 'unchanged':
                return '';
            case 'nested':
                return makePlain($node['children'], $newPath);
        }
    }, $diff);

    return flatten($nestedLines);
}
