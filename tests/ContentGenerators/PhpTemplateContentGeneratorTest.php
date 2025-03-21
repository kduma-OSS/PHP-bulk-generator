<?php

declare(strict_types=1);

namespace Kduma\BulkGenerator\Tests\ContentGenerators;

use Kduma\BulkGenerator\ContentGenerators\PhpTemplateContentGenerator;
use PHPUnit\Framework\TestCase;

final class PhpTemplateContentGeneratorTest extends TestCase
{
    public function testGetContent()
    {
        $phpTemplateContentGenerator = new PhpTemplateContentGenerator(__DIR__.'/../stubs/php_basic_template.php');

        $this->assertSame('Hello John!', $phpTemplateContentGenerator->getContent(['name' => 'John']));
    }
    
    public function testGetContentWithArray()
    {
        $phpTemplateContentGenerator = new PhpTemplateContentGenerator(__DIR__.'/../stubs/php_array_template.php');

        $this->assertSame('Hello John!', $phpTemplateContentGenerator->getContent([
            'user' => [
                'name' => 'John'
            ]
        ]));
    }
}
