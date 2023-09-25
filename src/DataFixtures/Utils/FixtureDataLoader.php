<?php

namespace App\DataFixtures\Utils;

class FixtureDataLoader
{
    public static function loadDataFromJson(string $filename): array
    {
        $content = file_get_contents(__DIR__ . sprintf('/../Data/%s', $filename));

        return json_decode($content, true);
    }
}