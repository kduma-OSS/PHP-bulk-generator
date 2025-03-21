<?php

declare(strict_types=1);

namespace Kduma\BulkGenerator\ContentGenerators;


use Kduma\BulkGenerator\ContentGenerators\Twig\BoxNode;
use Kduma\BulkGenerator\ContentGenerators\Twig\BoxTokenParser;
use Kduma\BulkGenerator\ContentGenerators\Twig\Loader\LoaderInterface;
use Twig\Compiler;
use Twig\Error\SyntaxError;
use Twig\Node\Expression\AbstractExpression;
use Twig\Node\Expression\ArrayExpression;
use Twig\Node\Node;
use Twig\Token;

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

    protected function buildTwigEnvironment(): \Twig\Environment
    {
        $arrayLoader = new \Twig\Loader\ArrayLoader([
                'index' => $this->template,
            ] + $this->partials);

        $twig = new \Twig\Environment($arrayLoader, [
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
