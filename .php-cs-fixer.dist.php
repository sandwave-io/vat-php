<?php

$config = new SandwaveIo\PhpCsFixerConfig\Config([
    'declare_strict_types' => true,
]);
$config->getFinder()
    ->in(__DIR__ . "/src")
    ->in(__DIR__ . "/tests");

return $config;