<?php


namespace Kduma\BulkGenerator\ContentGenerators;


use RecursiveArrayIterator;
use RecursiveIteratorIterator;

class SimpleTemplateWithPlaceholdersContentGenerator implements ContentGeneratorInterface
{
    /**
     * SimpleTemplateWithPlaceholdersContentGenerator constructor.
     *
     * @param string $template
     */
    public function __construct(private readonly string $template)
    {
    }
    
    private function dot(array $array): array {
        $iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($array));
        $result = [];
        foreach ($iterator as $leafValue) {
            $keys = [];
            foreach (range(0, $iterator->getDepth()) as $depth) {
                $keys[] = $iterator->getSubIterator($depth)->key();
            }

            $result[ implode('.', $keys) ] = $leafValue;
        }
        
        return $result;
    }

    public function getContent(array $variables): string
    {
        $variables = array_merge([], ...array_map(fn($value, $key) => is_array($value) ? [$key => json_encode($value)] + $this->dot([$key => $value]) : [$key => $value], array_values($variables), array_keys($variables)));

        $variables['variables'] = print_r($variables, true);

        return str_replace(
            array_map(fn($key) => sprintf('{%s}', $key), array_keys($variables)),
            array_values($variables), 
            $this->template
        );
    }
}