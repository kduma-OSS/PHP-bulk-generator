<?php

declare(strict_types=1);

namespace Kduma\BulkGenerator\PdfGenerators;


use Kduma\BulkGenerator\PageOptions\PageMargins;
use Kduma\BulkGenerator\PageOptions\PageSize;
use Mpdf\Mpdf;
use Mpdf\MpdfException;

class MpdfGenerator implements PdfGeneratorInterface
{
    private Mpdf $mpdf;

    private array $templates = [];

    private readonly PageSize $pageSize;

    private string $css = '';

    private bool $css_written = false;

    /**
     * MpdfGenerator constructor.
     *
     * @param PageSize|null $pageSize
     * @param PageMargins|null $pageMargins
     */
    public function __construct(?PageSize $pageSize = null, private readonly PageMargins $pageMargins = new PageMargins(0, 0, 0, 0, 0, 0))
    {
        $this->pageSize = $pageSize ?? PageSize::fromName('A4');
    }

    public function start(bool $doubleSided): void
    {
        $this->mpdf = new Mpdf([
            'mode' => 'utf-8',

            'format' => [$this->pageSize->getWidth(), $this->pageSize->getHeight()],
            'orientation' => $this->pageSize->isLandscape() ? 'L' : 'P',

            'mirrorMargins' => $doubleSided,

            'margin_left' => $this->pageMargins->getLeft(),
            'margin_right' => $this->pageMargins->getRight(),
            'margin_top' => $this->pageMargins->getTop(),
            'margin_bottom' => $this->pageMargins->getBottom(),
            'margin_header' => $this->pageMargins->getHeader(),
            'margin_footer' => $this->pageMargins->getFooter(),
        ]);

        $this->templates = [];
        $this->css_written = false;
    }

    public function insert(string $html, ?string $template = null): void
    {
        $this->mpdf->AddPage();

        if(!$this->css_written) {
            $this->mpdf->WriteHTML(sprintf('<style>%s</style>', $this->css));
            $this->css_written = true;
        }

        if($template) {
            if(!isset($this->templates[$template])) {
                $this->mpdf->SetSourceFile($template);
                $this->templates[$template] = $this->mpdf->ImportPage(1);
            }

            $this->mpdf->UseTemplate($this->templates[$template]);
        }

        $this->mpdf->WriteHTML($html);
    }

    public function finish(): string
    {
        return $this->mpdf->Output('', 'S');
    }

    public function setCss(string $css): MpdfGenerator
    {
        $this->css = $css;
        return $this;
    }
}
