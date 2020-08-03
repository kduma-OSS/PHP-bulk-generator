<?php


namespace Kduma\BulkGenerator\ContentGenerators\Twig\Loader;


use Twig\Environment;

interface LoaderInterface
{
    /**
     * @param Environment $environment
     *
     * @return Environment
     */
    public function load(Environment $environment): Environment;
}