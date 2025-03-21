<?php

declare(strict_types=1);

namespace Kduma\BulkGenerator\ContentGenerators\Twig\Loader;

use Twig\Environment;

class GenericLoader implements LoaderInterface
{
    /**
     * @var callable
     */
    private $callback;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function load(Environment $twigEnvironment): Environment
    {
        $callback = $this->callback;

        return $callback($twigEnvironment) ?? $twigEnvironment;
    }
}
