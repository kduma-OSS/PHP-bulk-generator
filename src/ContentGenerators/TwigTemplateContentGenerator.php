<?php


namespace Kduma\BulkGenerator\ContentGenerators;


use Kduma\BulkGenerator\ContentGenerators\Twig\BoxNode;
use Kduma\BulkGenerator\ContentGenerators\Twig\BoxTokenParser;
use Twig\Compiler;
use Twig\Error\SyntaxError;
use Twig\Node\Expression\AbstractExpression;
use Twig\Node\Expression\ArrayExpression;
use Twig\Node\Node;
use Twig\Token;

class TwigTemplateContentGenerator implements ContentGeneratorInterface
{
    private string $template;
    private array  $partials;

    /**
     * TwigTemplateContentGenerator constructor.
     *
     * @param string $template
     * @param array  $partials
     */
    public function __construct(string $template, array $partials = [])
    {
        $this->template = $template;
        $this->partials = $partials;
    }

    public function getContent(array $variables): string
    {
        $twig = $this->buildTwigEnvironment();

        return $twig->render('index', $variables);
    }

    /**
     * @return \Twig\Environment
     */
    protected function buildTwigEnvironment(): \Twig\Environment
    {
        $loader = new \Twig\Loader\ArrayLoader([
                'index' => $this->template,
            ] + $this->partials);

        $twig = new \Twig\Environment($loader, [
//            'cache' => __DIR__.'/../../cache/'
        ]);
        
        $twig->addTokenParser(new BoxTokenParser());
        
        return $twig;
    }
}