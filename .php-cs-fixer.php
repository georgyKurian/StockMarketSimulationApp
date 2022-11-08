<?php

$finder = PhpCsFixer\Finder::create()
    ->notPath('src/Symfony/Component/Translation/Tests/fixtures/resources.php')
    ->in([
        __DIR__ . '/app',
        __DIR__ . '/config',
        __DIR__ . '/database',
        __DIR__ . '/domain',
        __DIR__ . '/resources',
        __DIR__ . '/routes',
        __DIR__ . '/tests',
    ])
    ->name('*.php');

$config = new PhpCsFixer\Config();

return $config->setRules([
        '@PSR12' => true,
        'strict_param' => true,
        'array_syntax' => ['syntax' => 'short'],
    ])
    ->setFinder($finder)
;