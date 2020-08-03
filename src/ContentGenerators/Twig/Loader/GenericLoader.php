<?php


namespace Kduma\BulkGenerator\ContentGenerators\Twig\Loader;


use Twig\Environment;
use Twig\Extension\ExtensionInterface;

class GenericLoader implements LoaderInterface
{
    /**
     * @var callable
     */
    private $callback;

    /**
     * GenericLoader constructor.
     *
     * @param callable $callback
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * @inheritDoc
     */
    public function load(Environment $environment): Environment
    {
        $callback = $this->callback;
        
        return $callback($environment) ?? $environment;
    }
}