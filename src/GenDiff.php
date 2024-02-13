<?php

namespace GenDiff;

use function GenDiff\Parser\parseFile;
use function GenDiff\Formatters\Stylish\makeStylish;

function iter(array $data1, array $data2): array
{
    $keys = array_keys([...$data1, ...$data2]);
    sort($keys);
    return array_map(function ($key) use ($data1, $data2) {
        if (!array_key_exists($key, $data1)) {
            return [
                'type' => 'added',
                'key' => $key,
                'value' => $data2[$key],
            ];
        } elseif (!array_key_exists($key, $data2)) {
            return [
                'type' => 'deleted',
                'key' => $key,
                'value' => $data1[$key],
            ];
        } elseif (is_array($data1[$key]) && is_array($data2[$key])) {
            return [
                'type' => 'nested',
                'key' => $key,
                'children' => iter($data1[$key], $data2[$key]),
            ];
        } elseif ($data1[$key] !== $data2[$key]) {
            return [
                'type' => 'changed',
                'key' => $key,
                'oldValue' => $data1[$key],
                'newValue' => $data2[$key],
            ];
        } else {
            return [
                'type' => 'unchanged',
                'key' => $key,
                'value' => $data1[$key],
            ];
        }
    }, $keys);
}

function genDiff(string $filepath1, string $filepath2, string $formatName = 'stylish'): string
{
    $data1 = parseFile($filepath1);
    $data2 = parseFile($filepath2);

    $diff = iter($data1, $data2);

    return match ($formatName) {
        'stylish' => makeStylish($diff)
    };
}
