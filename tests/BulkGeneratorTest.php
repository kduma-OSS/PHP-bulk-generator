<?php

namespace Kduma\BulkGenerator\Tests;

use Kduma\BulkGenerator\BulkGenerator;
use Kduma\BulkGenerator\ContentGenerators\SimpleTemplateWithPlaceholdersContentGenerator;
use Kduma\BulkGenerator\DataSources\RangeCounterDataSource;
use Kduma\BulkGenerator\PageOptions\PageSize;
use Kduma\BulkGenerator\PdfGenerators\MpdfGenerator;
use PHPUnit\Framework\TestCase;

class BulkGeneratorTest extends TestCase
{
    public function testGenerate()
    {
        $dataSource = new RangeCounterDataSource(1, 100);
        
        $pdfGenerator = new MpdfGenerator(new PageSize(148.5, 105));
        
        $generator = (new BulkGenerator($dataSource, $pdfGenerator))
            ->setBackTemplate(__DIR__.'/stubs/back.pdf')
            ->setFrontTemplate(__DIR__.'/stubs/front.pdf')
            ->setFrontContentGenerator(new SimpleTemplateWithPlaceholdersContentGenerator("<div style=\"font-family: monospace; font-size:7.08mm; border:hidden solid #000; text-align:left; bottom: 49mm; position:absolute; left:1cm; right: 0\">
    {counter}
</div>

<div style=\"font-family: monospace; font-size:7.08mm; border:hidden solid #000; text-align:left; bottom: 32mm; position:absolute; left:1cm; right: 0\">
    {variables}
</div>

<div style=\"font-family: monospace; font-size:10px; text-align:center; bottom: 0.5cm; position:absolute; left:1cm;\">
    AAAAAAAAAAAAA
</div>"));
        
        $generator->generate(__DIR__.'/output.pdf');
    }
}
