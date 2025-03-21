<?php

namespace Kduma\BulkGenerator\Tests\ContentGenerators;

use Kduma\BulkGenerator\ContentGenerators\TwigTemplateContentGenerator;
use PHPUnit\Framework\TestCase;

class TwigTemplateContentGeneratorTest extends TestCase
{
    public function testGetContent()
    {
        $sut = new TwigTemplateContentGenerator('Hello {{name}}!');
        
        $this->assertSame('Hello John!', $sut->getContent(['name' => 'John']));
    }
    
    public function testGetContentWithArray()
    {
        $sut = new TwigTemplateContentGenerator('Hello {{user.name}}!');
        
        $this->assertSame('Hello John!', $sut->getContent([
            'user' => [
                'name' => 'John'
            ]
        ]));
    }
}
