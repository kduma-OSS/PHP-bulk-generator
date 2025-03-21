<?php

namespace Kduma\BulkGenerator\Tests\ContentGenerators;

use Kduma\BulkGenerator\ContentGenerators\PhpTemplateContentGenerator;
use PHPUnit\Framework\TestCase;

class PhpTemplateContentGeneratorTest extends TestCase
{
    public function testGetContent()
    {
        $sut = new PhpTemplateContentGenerator(__DIR__.'/../stubs/php_basic_template.php');

        $this->assertSame('Hello John!', $sut->getContent(['name' => 'John']));
    }
    
    public function testGetContentWithArray()
    {
        $sut = new PhpTemplateContentGenerator(__DIR__.'/../stubs/php_array_template.php');

        $this->assertSame('Hello John!', $sut->getContent([
            'user' => [
                'name' => 'John'
            ]
        ]));
    }
}
