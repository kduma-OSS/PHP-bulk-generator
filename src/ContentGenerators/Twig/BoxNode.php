<?php


namespace Kduma\BulkGenerator\ContentGenerators\Twig;


use Twig\Compiler;
use Twig\Node\Expression\AbstractExpression;
use Twig\Node\Expression\ArrayExpression;
use Twig\Node\Node;

class BoxNode extends Node
{
    private string $as = 'div';
    private bool   $bordered = false;

    public function __construct(Node $body, bool $bordered, ?AbstractExpression $attributes, AbstractExpression $left, AbstractExpression $top, ?AbstractExpression $width, ?AbstractExpression $height, ?string $as, ?AbstractExpression $table_attributes, int $lineno, string $tag = null)
    {
        $this->bordered = $bordered;
        
        $nodes = [
            'body' => $body,
            'left' => $left,
            'top' => $top,
        ];
        if (null !== $attributes) {
            $nodes['attributes'] = $attributes;
        }
        if (null !== $table_attributes) {
            $nodes['table_attributes'] = $table_attributes;
        }
        if (null !== $as) {
            $this->as = $as;
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
        if ($this->as == 'table') {
            $this->compileTable($compiler);
        } else {
            $this->compileDiv($compiler);
        }
    }

    /**
     * @param Compiler $compiler
     */
    protected function compileDiv(Compiler $compiler): void
    {
        $compiler
            ->write("\$attributes = collect(['style' => ['position' => 'absolute', 'left' => (")
            ->subcompile($this->getNode('left'))
            ->raw(").'mm', 'top' => (")
            ->subcompile($this->getNode('top'))
            ->raw(").'mm'");

        if ($this->hasNode('width')) {
            $compiler
                ->raw(", 'width' => (")
                ->subcompile($this->getNode('width'))
                ->raw(").'mm'");
        }

        if ($this->hasNode('height')) {
            $compiler
                ->raw(", 'height' => (")
                ->subcompile($this->getNode('height'))
                ->raw(").'mm'");
        }

        $compiler->raw("]])\n")
            ->indent();
        
        if ($this->bordered) {
            $compiler
                ->write("->mergeRecursive(['style' => ['border' => '0.1mm solid black']])\n");
        }

        if ($this->hasNode('attributes')) {
            $node = $this->getNode('attributes');

            if ($node instanceof ArrayExpression) {

                $compiler
                    ->write("->mergeRecursive(")
                    ->subcompile($node)
                    ->raw(")\n");
            }
        }
        
        $this->writeAttributesMapperCode($compiler);

        $compiler
            ->addDebugInfo($this)
            ->write('echo ')
            ->string("<div ")
            ->raw(".\$attributes.")
            ->string(">\n")
            ->raw(";\n")
            ->subcompile($this->getNode('body'))
            ->addDebugInfo($this)
            ->write('echo ')
            ->string("</div>\n")
            ->raw(";\n");
    }

    /**
     * @param Compiler $compiler
     */
    protected function compileTable(Compiler $compiler): void
    {
        $compiler
            ->write("\$div_attributes = collect(['style' => ['position' => 'absolute', 'padding' => '0', 'left' => (")
            ->subcompile($this->getNode('left'))
            ->raw(").'mm', 'top' => (")
            ->subcompile($this->getNode('top'))
            ->raw(").'mm'")
            ->raw("]])\n")
            ->indent();


        if ($this->bordered) {
            $compiler
                ->write("->mergeRecursive(['style' => ['border' => '0.1mm dotted red']])\n");
        }
        
        if ($this->hasNode('height')) {
            $compiler
                ->write("->mergeRecursive(['style' => ['height' => (")
                ->subcompile($this->getNode('height'))
                ->raw(").'mm']])\n");
        }
        
        if ($this->hasNode('width')) {
            $compiler
                ->write("->mergeRecursive(['style' => ['width' => (")
                ->subcompile($this->getNode('width'))
                ->raw(").'mm']])\n");
        }

        $this->writeAttributesMapperCode($compiler);
        
        $compiler
            ->write("\$attributes = collect(['style' => ['border-collapse' => 'collapse', 'overflow' => 'wrap']])\n")
            ->indent();
        
        if ($this->hasNode('table_attributes')) {
            $node = $this->getNode('table_attributes');

            if ($node instanceof ArrayExpression) {

                $compiler
                    ->write("->mergeRecursive(")
                    ->subcompile($node)
                    ->raw(")\n");
            }
        }

        $this->writeAttributesMapperCode($compiler);

        $compiler
            ->write("\$table_attributes = collect([])\n")
            ->indent();


        if ($this->hasNode('width')) {
            $compiler
                ->write("->mergeRecursive(['style' => ['width' => (")
                ->subcompile($this->getNode('width'))
                ->raw(").'mm']])\n");
        }
        
        if ($this->hasNode('height')) {
            $compiler
                ->write("->mergeRecursive(['style' => ['height' => (")
                ->subcompile($this->getNode('height'))
                ->raw(").'mm']])\n");
        }
        
        if ($this->bordered) {
            $compiler
                ->write("->mergeRecursive(['style' => ['border' => '0.1mm solid black']])\n");
        }

        if ($this->hasNode('attributes')) {
            $node = $this->getNode('attributes');

            if ($node instanceof ArrayExpression) {

                $compiler
                    ->write("->mergeRecursive(")
                    ->subcompile($node)
                    ->raw(")\n");
            }
        }

        $this->writeAttributesMapperCode($compiler);

        $compiler
            ->addDebugInfo($this)
            
            ->write('echo ')
            ->string("<div ")
            ->raw(".\$div_attributes.")
            ->string(">\n")
            ->raw(";\n")
            
            ->write('echo ')
            ->string("<table ")
            ->raw(".\$attributes.")
            ->string("><tr><td ")
            ->raw(".\$table_attributes.")
            ->string(">\n")
            ->raw(";\n")
            
            ->subcompile($this->getNode('body'))
            
            ->addDebugInfo($this)
            ->write('echo ')
            ->string("</td></tr></table>\n")
            ->raw(";\n")
            
            ->write('echo ')
            ->string("</div>\n")
            ->raw(";\n");
    }

    /**
     * @param Compiler $compiler
     */
    protected function writeAttributesMapperCode(Compiler $compiler): void
    {
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
            ->outdent();
    }
}