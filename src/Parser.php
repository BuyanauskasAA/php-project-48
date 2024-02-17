<?php

namespace Differ\Parser;

use Symfony\Component\Yaml\Yaml;

function parseFile(string $filepath): array
{
    $extension = pathinfo($filepath, PATHINFO_EXTENSION);
    $content = file_get_contents($filepath) === false ? "" : file_get_contents($filepath);
    return match ($extension) {
        'json' => json_decode($content, true),
        'yaml', 'yml' => Yaml::parse($content),
        default => "Wrong format {$extension}"
    };
}
