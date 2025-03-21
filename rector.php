<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([__DIR__ . '/samples', __DIR__ . '/src', __DIR__ . '/tests'])
    ->withSkip([__DIR__ . '/tests/stubs/'])
    ->withImportNames(removeUnusedImports: true)
    ->withPhpSets(php82: true)
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        codingStyle: true,
        typeDeclarations: true,
        privatization: true,
        //        strictBooleans: true,
        naming: true,
        instanceOf: true,
        earlyReturn: true,
        carbon: true,
        rectorPreset: true,
        phpunitCodeQuality: true,
    )
    ->withComposerBased(phpunit: true);
