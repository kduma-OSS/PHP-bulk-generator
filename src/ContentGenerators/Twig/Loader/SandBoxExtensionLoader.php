<?php


namespace Kduma\BulkGenerator\ContentGenerators\Twig\Loader;


use Twig\Environment;
use Twig\Extension\ExtensionInterface;
use Twig\Extension\SandboxExtension;
use Twig\Sandbox\SecurityPolicy;

class SandBoxExtensionLoader implements LoaderInterface
{
    /**
     * @inheritDoc
     */
    public function load(Environment $twigEnvironment): Environment
    {
        $tags = ['if'];

        $filters = ['upper', 'escape'];

        $methods = [
            'Article' => ['getTitle', 'getBody'],
        ];

        $properties = [
            'Article' => ['title', 'body'],
        ];

        $functions = ['range'];

        $securityPolicy = new SecurityPolicy($tags, $filters, $methods, $properties, $functions);

        $twigEnvironment->addExtension(new SandboxExtension($securityPolicy, true));

        return $twigEnvironment;
    }
}