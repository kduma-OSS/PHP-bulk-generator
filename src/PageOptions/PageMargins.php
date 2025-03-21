<?php

declare(strict_types=1);

namespace Kduma\BulkGenerator\PageOptions;


class PageMargins
{
    /**
     * PageMargins constructor.
     */
    public function __construct(private readonly float $left = 15, private readonly float $right = 15, private readonly float $top = 16, private readonly float $bottom = 16, private readonly float $header = 9, private readonly float $footer = 9)
    {
    }

    public static function makeByAxis(float $horizontal = 15, float $vertical = 16, float $header = 9, float $footer = 9): PageMargins
    {
        return new PageMargins($horizontal, $horizontal, $vertical, $vertical, $header, $footer);
    }

    public function getLeft(): float
    {
        return $this->left;
    }

    public function getRight(): float
    {
        return $this->right;
    }

    public function getTop(): float
    {
        return $this->top;
    }

    public function getBottom(): float
    {
        return $this->bottom;
    }

    public function getHeader(): float
    {
        return $this->header;
    }

    public function getFooter(): float
    {
        return $this->footer;
    }
}
