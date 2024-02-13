<?php

namespace GenDiff\Formatters\Stylish;

function stringify(mixed $value, $depth): string
{
    $line = '';
    $indentSize = 4;
    $bracketIndent = str_repeat(' ', $depth * $indentSize);
    $currentIndent = str_repeat(' ', ($depth + 1) * $indentSize);
    switch (gettype($value)) {
        case 'array':
            $lines = array_map(function ($key) use ($value, $currentIndent, $depth) {
                return "{$currentIndent}{$key}: " . stringify($value[$key], $depth + 1);
            }, array_keys($value));
            $line =  implode("\n", array_merge(['{'], $lines, ["{$bracketIndent}}"]));
            break;
        case 'boolean':
            $line = $value ? 'true' : 'false';
            break;
        case 'NULL':
            $line = 'null';
            break;
        default:
            $line = (string) $value;
    }

    return $line;
}


function makeStylish(array $diff, int $depth = 0): string
{

    $indentSize = 4;
    $indent = str_repeat(' ', $depth * $indentSize);
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
            'nested' => "{$indent}    {$key}: " . makeStylish($node['children'], $depth + 1)
        };
    }, $diff);

    return implode("\n", array_merge(['{'], $lines, ["{$indent}}"]));
}
