<?php

declare(strict_types=1);

namespace Kduma\BulkGenerator\ContentGenerators\Twig\Loader;


use Twig\Environment;

interface LoaderInterface
{
    /**
     * @param Environment $twigEnvironment
     *
     * @return Environment
     */
    public function load(Environment $twigEnvironment): Environment;
}
