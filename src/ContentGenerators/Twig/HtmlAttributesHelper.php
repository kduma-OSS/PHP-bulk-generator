<?php


namespace Kduma\BulkGenerator\ContentGenerators\Twig;


class HtmlAttributesHelper
{
    private array $values;

    /**
     * HtmlAttributesHelper constructor.
     *
     * @param array $values
     */
    public function __construct(array ...$values)
    {
        $this->values = $values;
    }

    public static function start(array ...$values): HtmlAttributesHelper
    {
        return new HtmlAttributesHelper(...$values);
    }

    public function add(array ...$values): HtmlAttributesHelper
    {
        $this->values = array_merge($this->values, $values);
        
        return $this;
    }
    
    public function __toString()
    {
        $values = [];

        foreach ($this->values as $arr) {
            foreach ($arr as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $p => $v) {
                        if($key == 'style')
                            $values[$key][$p] = $v;
                        else
                            $values[$key][] = $v;
                    }
                } else {
                    $values[$key][] = $value;
                }
            }
        }
        
        $values = array_merge([], ...array_map(function ($value, $key) {
            if(!is_array($value))
                return [$key => $value];

            if($key != 'style')
                return [$key => implode(' ', $value)];
            
            return [$key => implode('; ', array_map(fn($value, $key) => $key.': '.$value, array_values($value), array_keys($value)))];
        }, array_values($values), array_keys($values)));
        
        $values = array_map(function ($value, $key) {
            return $key.'="'.htmlentities($value).'"';
        }, array_values($values), array_keys($values));
        
        return implode(' ', $values);
    }
}