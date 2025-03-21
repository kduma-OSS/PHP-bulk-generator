<?php

namespace Kduma\BulkGenerator\Tests\ContentGenerators;

use Kduma\BulkGenerator\ContentGenerators\SimpleTemplateWithPlaceholdersContentGenerator;
use PHPUnit\Framework\TestCase;

class SimpleTemplateWithPlaceholdersContentGeneratorTest extends TestCase
{
    public function testGetContent()
    {
        $sut = new SimpleTemplateWithPlaceholdersContentGenerator('Hello {name}!');
        
        $this->assertSame('Hello John!', $sut->getContent(['name' => 'John']));
    }
    
    public function testGetContentWithArray()
    {
        $sut = new SimpleTemplateWithPlaceholdersContentGenerator('Hello {user.name}!');
        
        $this->assertSame('Hello John!', $sut->getContent([
            'user' => [
                'name' => 'John'
            ]
        ]));
    }
}
