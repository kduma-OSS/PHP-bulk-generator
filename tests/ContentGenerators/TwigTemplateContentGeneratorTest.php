<?php

namespace Kduma\BulkGenerator\Tests\ContentGenerators;

use Kduma\BulkGenerator\ContentGenerators\TwigTemplateContentGenerator;
use PHPUnit\Framework\TestCase;

class TwigTemplateContentGeneratorTest extends TestCase
{
    public function testGetContent()
    {
        $twigTemplateContentGenerator = new TwigTemplateContentGenerator('Hello {{name}}!');
        
        $this->assertSame('Hello John!', $twigTemplateContentGenerator->getContent(['name' => 'John']));
    }
    
    public function testGetContentWithArray()
    {
        $twigTemplateContentGenerator = new TwigTemplateContentGenerator('Hello {{user.name}}!');
        
        $this->assertSame('Hello John!', $twigTemplateContentGenerator->getContent([
            'user' => [
                'name' => 'John'
            ]
        ]));
    }
}
