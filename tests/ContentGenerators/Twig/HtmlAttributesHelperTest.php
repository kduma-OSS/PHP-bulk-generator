<?php

declare(strict_types=1);

namespace Kduma\BulkGenerator\Tests\ContentGenerators\Twig;

use Kduma\BulkGenerator\ContentGenerators\Twig\HtmlAttributesHelper;
use PHPUnit\Framework\TestCase;

final class HtmlAttributesHelperTest extends TestCase
{

    public function test(): void
    {
        $htmlAttributesHelper = HtmlAttributesHelper::start(['style' => ['position' => 'absolute', 'left' => '5mm']])
            ->add(["class" => "key"]);
        
        $this->assertSame('style="position: absolute; left: 5mm" class="key"', (string) $htmlAttributesHelper);
    }
}
