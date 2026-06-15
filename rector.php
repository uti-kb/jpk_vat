<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Switch_\ChangeSwitchToMatchRector;
use Rector\Php81\Rector\Property\ReadOnlyPropertyRector;
use Rector\ValueObject\PhpVersion;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        // examples/ celowo poza zakresem — to wyrocznia smoke-testów, trzymamy je stabilne
    ])
    ->withPhpVersion(PhpVersion::PHP_82)        // sufit składni = podłoga pakietu (>=8.2)
    ->withPhpSets()                             // zestawy do 8.2 z composer.json → str_contains/str_starts_with
    ->withPreparedSets(typeDeclarations: true)  // natywne deklaracje typów z PHPDoc/inferencji
    ->withSkip([
        // Trait z akumulatorami float ($sum/$tax) i statycznym $index round-tripującym
        // string z XML oraz tablicą $fields o mieszanych wartościach — bez auto-typowania.
        __DIR__ . '/src/Helper/BuySellRow.php',

        // match poza zakresem tej modernizacji (i tak brak switch w kodzie) — defensywnie.
        ChangeSwitchToMatchRector::class,

        // readonly poza zakresem tego przebiegu — w praktyce kwalifikuje się tylko V3/JPK::$company
        // (artefakt po usunięciu martwego `instanceof Subject`), co tworzyłoby niespójność
        // z V1/V2/V7M, gdzie ta sama właściwość nie jest readonly.
        ReadOnlyPropertyRector::class,
    ]);
