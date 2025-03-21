<?php


namespace Kduma\BulkGenerator\ContentGenerators\Twig;


use Twig\Error\SyntaxError;
use Twig\Node\Node;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

class BoxTokenParser extends AbstractTokenParser
{
    public function parse(Token $token): Node
    {
        $parser = $this->parser;
        $tokenStream = $parser->getStream();

        $left = $parser->getExpressionParser()->parseExpression();
        $tokenStream->expect(Token::PUNCTUATION_TYPE, ',');
        $top = $parser->getExpressionParser()->parseExpression();
        if($tokenStream->nextIf(Token::PUNCTUATION_TYPE, ','))
            $width = $parser->getExpressionParser()->parseExpression();

        if($tokenStream->nextIf(Token::PUNCTUATION_TYPE, ','))
            $height = $parser->getExpressionParser()->parseExpression();


        if ($tokenStream->nextIf(Token::NAME_TYPE, 'with')) {
            $attributes = $parser->getExpressionParser()->parseExpression();
        }

        if ($tokenStream->nextIf(Token::NAME_TYPE, 'as')) {
            $as = $tokenStream->expect(Token::NAME_TYPE)->getValue();

            if ($tokenStream->nextIf(Token::NAME_TYPE, 'with')) {
                $table_attributes = $parser->getExpressionParser()->parseExpression();
            }
        }

        $bordered = !! $tokenStream->nextIf(Token::NAME_TYPE, 'bordered');

        $tokenStream->expect(Token::BLOCK_END_TYPE);

        $node = $this->parser->subparse($this->decideBlockEnd(...), true);
        $tokenStream->expect(Token::BLOCK_END_TYPE);

        return new BoxNode($node, $bordered, $attributes ?? null, $left, $top, $width ?? null, $height ?? null, $as ?? null, $table_attributes ?? null, $token->getLine(), $this->getTag());
    }

    public function decideBlockEnd(Token $token): bool
    {
        return $token->test('endBox');
    }

    public function getTag(): string
    {
        return 'box';
    }
}