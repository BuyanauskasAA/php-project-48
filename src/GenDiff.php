<?php

namespace GenDiff;

use function GenDiff\Utils\formatToString;

function genDiff(string $filepath1, string $filepath2): string
{
    $data1 = json_decode(file_get_contents($filepath1), true);
    $data2 = json_decode(file_get_contents($filepath2), true);

    $keys = array_keys([...$data1, ...$data2]);
    sort($keys);

    $diffs = array_map(function ($key) use ($data1, $data2) {
        $result = [];
        if (!array_key_exists($key, $data1)) {
            $value = formatToString($data2[$key]);
            $result[] = "  + {$key}: {$value}";
        } elseif (!array_key_exists($key, $data2)) {
            $value = formatToString($data1[$key]);
            $result[] = "  - {$key}: {$value}";
        } elseif ($data1[$key] !== $data2[$key]) {
            $value1 = formatToString($data1[$key]);
            $value2 = formatToString($data2[$key]);
            $result[] = "  - {$key}: {$value1}";
            $result[] = "  + {$key}: {$value2}";
        } else {
            $value = formatToString($data1[$key]);
            $result[] = "    {$key}: {$value}";
        }

        return $result;
    }, $keys);

    $diffs = array_merge(['{'], ...$diffs);
    $diffs[] = '}';
    $diffString = implode("\n", $diffs);

    return $diffString;
}
