<?php


namespace Kduma\BulkGenerator\ContentGenerators;


use RecursiveArrayIterator;
use RecursiveIteratorIterator;

class PhpTemplateContentGenerator implements ContentGeneratorInterface
{
    private string $template;

    /**
     * PhpTemplateContentGenerator constructor.
     *
     * @param string $template
     */
    public function __construct(string $template)
    {
        $this->template = $template;
    }

    public function getContent(array $variables): string
    {
        ob_start();
        
        extract($variables);
        require $this->template;
        
        return ob_get_clean();
    }
}