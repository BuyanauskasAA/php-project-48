<?php

namespace Differ\Formatters\Json;

function makeJson(array $diff): string
{
    return json_encode($diff, JSON_PRETTY_PRINT);
}
