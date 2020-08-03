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
    public function load(Environment $environment): Environment
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

        $policy = new SecurityPolicy($tags, $filters, $methods, $properties, $functions);

        $environment->addExtension(new SandboxExtension($policy, true));

        return $environment;
    }
}