<?php

namespace Differ\Formatters\Stylish;

const INDENT_SIZE = 4;

function stringify(mixed $value, int $depth): string
{
    $bracketIndent = str_repeat(' ', $depth * INDENT_SIZE);
    $currentIndent = str_repeat(' ', ($depth + 1) * INDENT_SIZE);

    switch (gettype($value)) {
        case 'array':
            $lines = array_map(function ($key) use ($value, $currentIndent, $depth) {
                return "{$currentIndent}{$key}: " . stringify($value[$key], $depth + 1);
            }, array_keys($value));
            return  implode("\n", array_merge(['{'], $lines, ["{$bracketIndent}}"]));
        case 'boolean':
            return $value ? 'true' : 'false';
        case 'NULL':
            return 'null';
        default:
            return (string) $value;
    }
}


function makeStylish(array $diff, int $depth = 0): string
{

    $indent = str_repeat(' ', $depth * INDENT_SIZE);
    $lines = array_map(function ($node) use ($depth, $indent) {
        ['type' => $type, 'key' => $key] = $node;
        return match ($type) {
            'added' => "{$indent}  + {$key}: " . stringify($node['value'], $depth + 1),
            'deleted' => "{$indent}  - {$key}: " . stringify($node['value'], $depth + 1),
            'changed' =>
            "{$indent}  - {$key}: " . stringify($node['oldValue'], $depth + 1)
                . "\n" .
                "{$indent}  + {$key}: " . stringify($node['newValue'], $depth + 1),
            'unchanged' => "{$indent}    {$key}: " . stringify($node['value'], $depth + 1),
            'nested' => "{$indent}    {$key}: " . makeStylish($node['children'], $depth + 1),
            default => "Wrong node type: {$type}"
        };
    }, $diff);

    return implode("\n", array_merge(['{'], $lines, ["{$indent}}"]));
}
