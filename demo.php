<?php

use Kduma\BulkGenerator\ContentGenerators\TwigTemplateContentGenerator;
use Kduma\BulkGenerator\DataSources\PassthroughDataSource;
use Kduma\BulkGenerator\PdfGenerators\MpdfGenerator;
use Kduma\BulkGenerator\PageOptions\PageSize;
use Kduma\BulkGenerator\BulkGenerator;

require __DIR__.'/vendor/autoload.php';

$dataSource = new PassthroughDataSource(collect([
    [
        'id' => '1',
        'name' => 'John',
        'city' => 'Washington',
    ],
    [
        'id' => '2',
        'name' => 'Michael',
        'city' => 'New York',
    ],
]));

$pdfGenerator = new MpdfGenerator(new PageSize(100, 55));
$pdfGenerator->setCss(/** @lang CSS */ <<<CSS
    .key, .id {
        font-family: monospace; 
    }
    
    .key {
        font-size:10mm; 
    }
    
    .id {
        font-size:5mm; 
    }
    CSS
);

$content = new TwigTemplateContentGenerator(/** @lang Twig */ <<<Twig
    {% box 5, 5 with {"class": ["key"]} %}
    {{ name }}
    {% endBox %}
    
    {% box 5, 25 with {"class": "key"} %}
    {{ city }}
    {% endBox %}
    
    {% box 5, 45 with {"class": "id"} %}
    {{ id }}
    {% endBox %}
    Twig
);
$generator = (new BulkGenerator($dataSource, $pdfGenerator))
//    ->setFrontTemplate(__DIR__.'/template.pdf')
    ->setFrontContentGenerator($content);

$generator->generate(__DIR__.'/output.pdf');