<?php

namespace Differ\Differ;

use function Differ\Parser\parseFile;
use function Differ\Formatters\Stylish\makeStylish;
use function Differ\Formatters\Plain\makePlain;
use function Differ\Formatters\Json\makeJson;

function getDiffNode(string $type, string $key, mixed $value): array
{
    return match ($type) {
        'added' => ['type' => 'added', 'key' => $key, 'value' => $value],
        'deleted' => ['type' => 'deleted', 'key' => $key, 'value' => $value],
        'changed' => [
            'type' => 'changed',
            'key' => $key,
            'oldValue' => $value['oldValue'],
            'newValue' => $value['newValue']
        ],
        'unchanged' => ['type' => 'unchanged', 'key' => $key, 'value' => $value,],
        'nested' => ['type' => 'nested', 'key' => $key, 'children' => $value,]
    };
}

function iter(array $data1, array $data2): array
{
    $keys = array_keys([...$data1, ...$data2]);
    sort($keys);
    return array_map(function ($key) use ($data1, $data2) {
        if (!array_key_exists($key, $data1)) {
            return getDiffNode('added', $key, $data2[$key]);
        } elseif (!array_key_exists($key, $data2)) {
            return getDiffNode('deleted', $key, $data1[$key]);
        } elseif (is_array($data1[$key]) && is_array($data2[$key])) {
            return getDiffNode('nested', $key, iter($data1[$key], $data2[$key]));
        } elseif ($data1[$key] !== $data2[$key]) {
            return getDiffNode('changed', $key, ['oldValue' => $data1[$key], 'newValue' => $data2[$key]]);
        } else {
            return getDiffNode('unchanged', $key, $data1[$key]);
        }
    }, $keys);
}

function genDiff(string $filepath1, string $filepath2, string $formatName = 'stylish'): string
{
    $data1 = parseFile($filepath1);
    $data2 = parseFile($filepath2);

    $diff = iter($data1, $data2);

    return match ($formatName) {
        'stylish' => makeStylish($diff),
        'plain' => makePlain($diff),
        'json' => makeJson($diff)
    };
}
