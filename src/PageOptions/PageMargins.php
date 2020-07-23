<?php


namespace Kduma\BulkGenerator\PageOptions;


class PageMargins
{
    private float $left;
    private float $right;
    private float $top;
    private float $bottom;
    private float $header;
    private float $footer;

    /**
     * PageMargins constructor.
     *
     * @param float $left
     * @param float $right
     * @param float $top
     * @param float $bottom
     * @param float $header
     * @param float $footer
     */
    public function __construct(float $left = 15, float $right = 15, float $top = 16, float $bottom = 16, float $header = 9, float $footer = 9)
    {
        $this->left = $left;
        $this->right = $right;
        $this->top = $top;
        $this->bottom = $bottom;
        $this->header = $header;
        $this->footer = $footer;
    }

    public static function makeByAxis(float $horizontal = 15, float $vertical = 16, float $header = 9, float $footer = 9): PageMargins
    {
        return new PageMargins($horizontal, $horizontal, $vertical, $vertical, $header, $footer);
    }

    /**
     * @return float
     */
    public function getLeft(): float
    {
        return $this->left;
    }

    /**
     * @return float
     */
    public function getRight(): float
    {
        return $this->right;
    }

    /**
     * @return float
     */
    public function getTop(): float
    {
        return $this->top;
    }

    /**
     * @return float
     */
    public function getBottom(): float
    {
        return $this->bottom;
    }

    /**
     * @return float
     */
    public function getHeader(): float
    {
        return $this->header;
    }

    /**
     * @return float
     */
    public function getFooter(): float
    {
        return $this->footer;
    }
}