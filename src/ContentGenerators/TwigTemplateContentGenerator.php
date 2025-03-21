<?php

declare(strict_types=1);

namespace Kduma\BulkGenerator\ContentGenerators;


use Twig\Environment;
use Twig\Loader\ArrayLoader;
use Kduma\BulkGenerator\ContentGenerators\Twig\BoxTokenParser;
use Kduma\BulkGenerator\ContentGenerators\Twig\Loader\LoaderInterface;

class TwigTemplateContentGenerator implements ContentGeneratorInterface
{
    /**
     * @var LoaderInterface[]
     */
    private array $loaders = [];

    /**
     * TwigTemplateContentGenerator constructor.
     */
    public function __construct(private readonly string $template, private readonly array $partials = [])
    {
    }

    public function getContent(array $variables): string
    {
        $twigEnvironment = $this->buildTwigEnvironment();

        return $twigEnvironment->render('index', $variables);
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
    public function setLoaders(LoaderInterface ...$loaders): TwigTemplateContentGenerator
    {
        $this->loaders = $loaders;
        return $this;
    }

    public function addLoader(LoaderInterface $loader): TwigTemplateContentGenerator
    {
        $this->loaders[] = $loader;
        return $this;
    }
}
