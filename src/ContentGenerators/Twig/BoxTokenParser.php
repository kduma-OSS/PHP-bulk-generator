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
        $stream = $parser->getStream();

        $left = $parser->getExpressionParser()->parseExpression();
        $stream->expect(Token::PUNCTUATION_TYPE, ',');
        $top = $parser->getExpressionParser()->parseExpression();
        if($stream->nextIf(Token::PUNCTUATION_TYPE, ','))
            $width = $parser->getExpressionParser()->parseExpression();
        if($stream->nextIf(Token::PUNCTUATION_TYPE, ','))
            $height = $parser->getExpressionParser()->parseExpression();

        
        if ($stream->nextIf(Token::NAME_TYPE, 'with')) {
            $attributes = $parser->getExpressionParser()->parseExpression();
        }
        
        if ($stream->nextIf(Token::NAME_TYPE, 'as')) {
            $as = $stream->expect(Token::NAME_TYPE)->getValue();
            
            if ($stream->nextIf(Token::NAME_TYPE, 'with')) {
                $table_attributes = $parser->getExpressionParser()->parseExpression();
            }
        }

        $bordered = !! $stream->nextIf(Token::NAME_TYPE, 'bordered');

        $stream->expect(Token::BLOCK_END_TYPE);

        $body = $this->parser->subparse([$this, 'decideBlockEnd'], true);
        $stream->expect(Token::BLOCK_END_TYPE);

        return new BoxNode($body, $bordered, $attributes ?? null, $left, $top, $width ?? null, $height ?? null, $as ?? null, $table_attributes ?? null, $token->getLine(), $this->getTag());
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