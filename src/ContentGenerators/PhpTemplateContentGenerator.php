<?php

declare(strict_types=1);

namespace Kduma\BulkGenerator\ContentGenerators;


use RecursiveArrayIterator;
use RecursiveIteratorIterator;

class PhpTemplateContentGenerator implements ContentGeneratorInterface
{
    /**
     * PhpTemplateContentGenerator constructor.
     *
     * @param string $template
     */
    public function __construct(private readonly string $template)
    {
    }

    public function getContent(array $variables): string
    {
        ob_start();
        
        extract($variables);
        require $this->template;
        
        return ob_get_clean();
    }
}
