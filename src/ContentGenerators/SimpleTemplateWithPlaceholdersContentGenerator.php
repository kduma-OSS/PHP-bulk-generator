<?php


namespace Kduma\BulkGenerator\ContentGenerators;


use RecursiveArrayIterator;
use RecursiveIteratorIterator;

class SimpleTemplateWithPlaceholdersContentGenerator implements ContentGeneratorInterface
{
    private string $template;

    /**
     * SimpleTemplateWithPlaceholdersContentGenerator constructor.
     *
     * @param string $template
     */
    public function __construct(string $template)
    {
        $this->template = $template;
    }
    
    private function dot(array $array): array {
        $iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($array));
        $result = [];
        foreach ($iterator as $leafValue) {
            $keys = [];
            foreach (range(0, $iterator->getDepth()) as $depth) {
                $keys[] = $iterator->getSubIterator($depth)->key();
            }
            $result[ join('.', $keys) ] = $leafValue;
        }
        
        return $result;
    }

    public function getContent(array $variables): string
    {
        $variables = collect($variables)
            ->flatMap(function ($value, $key) {
                return is_array($value) ? [$key => json_encode($value)] + $this->dot([$key => $value]) : [$key => $value];
            });

        $variables['variables'] = print_r($variables->toArray(), true);

        return str_replace($variables->keys()->map(fn($key) => "{{$key}}")->toArray(), $variables->values()->toArray(), $this->template);
    }
}