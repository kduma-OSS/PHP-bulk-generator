<?php

declare(strict_types=1);

namespace Kduma\BulkGenerator\ContentGenerators;

use Kduma\BulkGenerator\ContentGenerators\Twig\BoxTokenParser;
use Kduma\BulkGenerator\ContentGenerators\Twig\Loader\LoaderInterface;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

class TwigTemplateContentGenerator implements ContentGeneratorInterface
{
    /**
     * @var LoaderInterface[]
     */
    private array $loaders = [];

    public function __construct(
        private readonly string $template,
        private readonly array $partials = []
    ) {
    }

    public function getContent(array $variables): string
    {
        $twigEnvironment = $this->buildTwigEnvironment();

        return $twigEnvironment->render('index', $variables);
    }

    /**
     * @return LoaderInterface[]
     */
    public function getLoaders(): array
    {
        return $this->loaders;
    }

    /**
     * @param LoaderInterface[] $loaders
     */
    public function setLoaders(LoaderInterface ...$loaders): self
    {
        $this->loaders = $loaders;
        return $this;
    }

    public function addLoader(LoaderInterface $loader): self
    {
        $this->loaders[] = $loader;
        return $this;
    }

    protected function buildTwigEnvironment(): Environment
    {
        $arrayLoader = new ArrayLoader([
            'index' => $this->template,
        ] + $this->partials);

        $twig = new Environment($arrayLoader, [
            //            'cache' => __DIR__.'/../../cache/'
        ]);

        $twig->addTokenParser(new BoxTokenParser());

        foreach ($this->loaders as $loader) {
            $twig = $loader->load($twig);
        }

        return $twig;
    }
}
