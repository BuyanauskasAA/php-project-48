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

        $line = '';
        switch ($node['type']) {
            case 'added':
                $line = "Property '{$newPath}' was added with value: " . stringify($node['value']);
                break;
            case 'deleted':
                $line = "Property '{$newPath}' was removed";
                break;
            case 'changed':
                $oldValue = stringify($node['oldValue']);
                $newValue = stringify($node['newValue']);
                $line = "Property '{$newPath}' was updated. From {$oldValue} to {$newValue}";
                break;
            case 'unchanged':
                $line = '';
                break;
            case 'nested':
                $line = makePlain($node['children'], $newPath);
                break;
        }

        return $line;
    }, $diff);

    return flatten($nestedLines);
}
