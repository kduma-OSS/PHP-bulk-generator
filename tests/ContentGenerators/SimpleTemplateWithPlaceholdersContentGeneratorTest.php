<?php

namespace Kduma\BulkGenerator\Tests\ContentGenerators;

use Kduma\BulkGenerator\ContentGenerators\SimpleTemplateWithPlaceholdersContentGenerator;
use PHPUnit\Framework\TestCase;

class SimpleTemplateWithPlaceholdersContentGeneratorTest extends TestCase
{
    public function testGetContent()
    {
        $simpleTemplateWithPlaceholdersContentGenerator = new SimpleTemplateWithPlaceholdersContentGenerator('Hello {name}!');
        
        $this->assertSame('Hello John!', $simpleTemplateWithPlaceholdersContentGenerator->getContent(['name' => 'John']));
    }
    
    public function testGetContentWithArray()
    {
        $simpleTemplateWithPlaceholdersContentGenerator = new SimpleTemplateWithPlaceholdersContentGenerator('Hello {user.name}!');
        
        $this->assertSame('Hello John!', $simpleTemplateWithPlaceholdersContentGenerator->getContent([
            'user' => [
                'name' => 'John'
            ]
        ]));
    }
}
