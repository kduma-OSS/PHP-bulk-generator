<?php

declare(strict_types=1);

use Kduma\BulkGenerator\BulkGenerator;
use Kduma\BulkGenerator\ContentGenerators\TwigTemplateContentGenerator;
use Kduma\BulkGenerator\DataSources\PassthroughDataSource;
use Kduma\BulkGenerator\PageOptions\PageSize;
use Kduma\BulkGenerator\PdfGenerators\MpdfGenerator;

require __DIR__ . '/../vendor/autoload.php';

function str_rand(int $length = 64): string // 64 = 32
{
    $length = max(4, $length);
    return strtoupper(bin2hex(random_bytes(($length - ($length % 2)) / 2)));
}

$dataSource = new PassthroughDataSource(
    array_map(fn ($row): array => [
        'number' => $row,
        'barcode' => random_int(100000, 999999),
        'username' => str_rand(8),
        'password' => str_rand(8),
    ], range(1, 100))
);

$pdfGenerator = new MpdfGenerator(new PageSize(95, 55));
$pdfGenerator->setCss(/** @lang CSS */ <<<CSS
    .password, .username {
        font-family: monospace; 
        font-size:4mm; 
        font-weight: bold;
        padding: 0;
        line-height: 0.7;
    }

    .center {
        vertical-align: middle;
        text-align: center;
    }

    .barcode {
        padding: 1.5mm;
        margin: 0;
        vertical-align: top;
        color: #000044;
    }

    .barcodecell {
        text-align: center;
        vertical-align: middle;
    }
    CSS
);

$content = new TwigTemplateContentGenerator(/** @lang Twig */ <<<Twig
    {% box 57, 31, 33 with {"class": "username center"} %}
        <div class="barcodecell">
            <barcode code="{{ barcode }}" type="C128C" class="barcode" />
        </div>
        {{ barcode }}
    {% endBox %}

    {% box 32, 33, 22, 6 with {"class": "username center"} as table %}
        {{ username }}
    {% endBox %}

    {% box 32, 42, 22, 6 with {"class": "password center"} as table %}
        {{ password }}
    {% endBox %}
    Twig
);

$generator = (new BulkGenerator($dataSource, $pdfGenerator))
    ->setFrontTemplate(__DIR__ . '/internet-access-card.template.pdf')
    ->setFrontContentGenerator($content);

$generator->generate(__DIR__ . '/internet-access-card.output.pdf');
