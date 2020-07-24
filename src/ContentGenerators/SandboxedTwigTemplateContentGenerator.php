<?php


namespace Kduma\BulkGenerator\ContentGenerators;


use Twig\Environment;
use Twig\Extension\SandboxExtension;

class SandboxedTwigTemplateContentGenerator extends TwigTemplateContentGenerator
{
    protected function buildTwigEnvironment(): Environment
    {
        $twig = parent::buildTwigEnvironment();
        
        $tags = ['if'];
        
        $filters = ['upper', 'escape'];
        
        $methods = [
            'Article' => ['getTitle', 'getBody'],
        ];
        
        $properties = [
            'Article' => ['title', 'body'],
        ];
        
        $functions = ['range'];
        
        $policy = new \Twig\Sandbox\SecurityPolicy($tags, $filters, $methods, $properties, $functions);
        
        $twig->addExtension(new SandboxExtension($policy, true));
        
        return $twig; 
    }
}