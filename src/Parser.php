<?php

namespace Differ\Parser;

use Symfony\Component\Yaml\Yaml;

function parseFile(string $filepath): array
{
    $extension = pathinfo($filepath, PATHINFO_EXTENSION);
    return match ($extension) {
        'json' => json_decode(file_get_contents($filepath), true),
        'yaml', 'yml' => Yaml::parseFile($filepath)
    };
}
