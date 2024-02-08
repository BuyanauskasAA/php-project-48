<?php

namespace GenDiff;

function genDiff(string $filepath1, string $filepath2): string
{
    $data1 = json_decode(file_get_contents($filepath1), true);
    $data2 = json_decode(file_get_contents($filepath2), true);

    $keys = array_keys([...$data1, ...$data2]);
    sort($keys);

    $diffs = array_map(function ($key) use ($data1, $data2){
        if (!array_key_exists($key, $data1)) {
            return ['  + ' . $key . ': ' . var_export($data2[$key], true)];
        } elseif (!array_key_exists($key, $data2)) {
            return ['  - ' . $key . ': ' . var_export($data1[$key], true)];
        } elseif ($data1[$key] !== $data2[$key]) {
            return ['  - ' . $key . ': ' . var_export($data1[$key], true), '  + ' . $key . ': ' . var_export($data2[$key], true)];
        } else {
            return ['    ' . $key . ': ' . var_export($data1[$key], true)];
        }
    }, $keys);
    $diffs = array_merge(['{'], ...$diffs);
    $diffs[] = '}';
    $diffString = implode("\n", $diffs);

    return $diffString;
}
