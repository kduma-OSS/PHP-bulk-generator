<?php

declare(strict_types=1);

namespace Kduma\BulkGenerator\ContentGenerators\Twig\Loader;

use Twig\Environment;

interface LoaderInterface
{
    public function load(Environment $twigEnvironment): Environment;
}
