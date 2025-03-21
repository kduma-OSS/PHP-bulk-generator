<?php

namespace Kduma\BulkGenerator\Tests\ContentGenerators\Twig;

use Kduma\BulkGenerator\ContentGenerators\Twig\HtmlAttributesHelper;
use PHPUnit\Framework\TestCase;

class HtmlAttributesHelperTest extends TestCase
{

    public function test()
    {
        $sut = HtmlAttributesHelper::start(['style' => ['position' => 'absolute', 'left' => '5mm']])
            ->add(["class" => "key"]);
        
        $this->assertSame('style="position: absolute; left: 5mm" class="key"', (string) $sut);
    }
}
