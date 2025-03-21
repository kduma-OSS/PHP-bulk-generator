<?php

declare(strict_types=1);

namespace Kduma\BulkGenerator\Tests\ContentGenerators;

use Kduma\BulkGenerator\ContentGenerators\TwigTemplateContentGenerator;
use PHPUnit\Framework\TestCase;

final class TwigTemplateContentGeneratorTest extends TestCase
{
    public function testGetContent(): void
    {
        $twigTemplateContentGenerator = new TwigTemplateContentGenerator('Hello {{name}}!');
        
        $this->assertSame('Hello John!', $twigTemplateContentGenerator->getContent(['name' => 'John']));
    }
    
    public function testGetContentWithArray(): void
    {
        $twigTemplateContentGenerator = new TwigTemplateContentGenerator('Hello {{user.name}}!');
        
        $this->assertSame('Hello John!', $twigTemplateContentGenerator->getContent([
            'user' => [
                'name' => 'John'
            ]
        ]));
    }
}
