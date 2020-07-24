<?php


namespace Kduma\BulkGenerator\ContentGenerators;


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


        $twig->addTokenParser(new class extends \Twig\TokenParser\AbstractTokenParser {
            public function parse(Token $token): Node
            {
                $parser = $this->parser;
                $stream = $parser->getStream();

                $left = $parser->getExpressionParser()->parseExpression();
                $stream->expect(\Twig\Token::PUNCTUATION_TYPE, ',');
                $top = $parser->getExpressionParser()->parseExpression();
                if($stream->nextIf(\Twig\Token::PUNCTUATION_TYPE, ','))
                    $width = $parser->getExpressionParser()->parseExpression();
                if($stream->nextIf(\Twig\Token::PUNCTUATION_TYPE, ','))
                    $height = $parser->getExpressionParser()->parseExpression();
                
                if ($stream->nextIf(\Twig\Token::NAME_TYPE, 'with')) {
                    $attributes = $parser->getExpressionParser()->parseExpression();
                }
                
                $stream->expect(\Twig\Token::BLOCK_END_TYPE);
                
                $body = $this->parser->subparse([$this, 'decideBlockEnd'], true);
                $stream->expect(\Twig\Token::BLOCK_END_TYPE);
                
                return new class ($body, $attributes, $left, $top, $width ?? null, $height ?? null, $token->getLine(), $this->getTag()) extends Node {
                    public function __construct(Node $body, ?AbstractExpression $attributes, AbstractExpression $left, AbstractExpression $top, ?AbstractExpression $width, ?AbstractExpression $height, int $lineno, string $tag = null)
                    {
                        $nodes = [
                            'body' => $body,
                            'left' => $left,
                            'top' => $top,
                        ];
                        if (null !== $attributes) {
                            $nodes['attributes'] = $attributes;
                        }
                        if (null !== $width) {
                            $nodes['width'] = $width;
                        }
                        if (null !== $height) {
                            $nodes['height'] = $height;
                        }
                        
                        parent::__construct($nodes, [], $lineno, $tag);
                    }

                    public function compile(Compiler $compiler): void
                    {
                        $compiler
                            ->write("\$attributes = collect(['style' => ['position' => 'absolute', 'left' => (")
                            ->subcompile($this->getNode('left'))
                            ->raw(").'mm', 'top' => (")
                            ->subcompile($this->getNode('top'))
                            ->raw(").'mm'");

                        if($this->hasNode('width')){
                            $compiler
                                ->raw(", 'width' => (")
                                ->subcompile($this->getNode('width'))
                                ->raw(").'mm'");
                        }

                        if($this->hasNode('height')){
                            $compiler
                                ->raw(", 'height' => (")
                                ->subcompile($this->getNode('height'))
                                ->raw(").'mm'");
                        }

                        $compiler->raw("]])\n")
                            ->indent();
                        
                        if($this->hasNode('attributes')){
                            $node = $this->getNode('attributes');

                            if($node instanceof ArrayExpression) {

                                $compiler
                                    ->write("->mergeRecursive(")
                                    ->subcompile($node)
                                    ->raw(")\n");
                            }
                        }

                        $compiler
                            ->write("->map(function (\$value, \$key) {\n")
                            ->indent()
                            ->write("if(!is_array(\$value))\n")
                            ->indent()
                            ->write("return \$value;\n\n")
                            ->outdent()
                            ->write("if(\$key != 'style')\n")
                            ->indent()
                            ->write("return collect(\$value)\n")
                            ->indent()
                            ->write("->implode(' ');\n\n")
                            ->outdent()
                            ->outdent()
                            ->write("return collect(\$value)\n")
                            ->indent()
                            ->write("->map(fn(\$value, \$key) => \$key.': '.\$value)\n")
                            ->write("->implode('; ');\n")
                            ->outdent()
                            ->outdent()
                            ->write("})\n")
                            ->write("->map(function (\$value, \$key) {\n")
                            ->indent()
                            ->write("return \$key.'=\"'.htmlentities(\$value).'\"';\n")
                            ->outdent()
                            ->write("})\n")
                            ->write("->implode(' ');\n\n")
                            ->outdent()
                        ;
                        
                        
                        $compiler
                            ->addDebugInfo($this)
                            
                            ->write('echo ')
                            ->string("<div ")
                            ->raw(".\$attributes.")
                            ->string(">")
                            ->raw(";\n")

                            ->subcompile($this->getNode('body'))

                            ->addDebugInfo($this)
                            ->write('echo ')
                            ->string("</div>")
                            ->raw(";\n");
                    }
                };
            }

            public function decideBlockEnd(Token $token): bool
            {
                return $token->test('endBox');
            }

            public function getTag(): string
            {
                return 'box';
            }
        });
        
        return $twig;
    }
}