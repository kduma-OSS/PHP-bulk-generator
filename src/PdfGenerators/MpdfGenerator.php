<?php


namespace Kduma\BulkGenerator\PdfGenerators;


use Kduma\BulkGenerator\PageOptions\PageMargins;
use Kduma\BulkGenerator\PageOptions\PageSize;

class MpdfGenerator implements PdfGeneratorInterface
{
    private \Mpdf\Mpdf $mpdf;
    private array $templates = [];
    private PageSize $pageSize;
    private PageMargins $pageMargins;
    private string $css = '';
    private bool $css_written = false;

    /**
     * MpdfGenerator constructor.
     *
     * @param PageSize    $pageSize
     * @param PageMargins $pageMargins
     */
    public function __construct(PageSize $pageSize = null, PageMargins $pageMargins = null)
    {
        $this->pageSize = $pageSize ?? PageSize::fromName('A4');
        $this->pageMargins = $pageMargins ?? new PageMargins(0, 0, 0, 0, 0, 0);
    }

    public function start(bool $doubleSided)
    {
        $this->mpdf = new \Mpdf\Mpdf([
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

    public function insert(string $html, string $template = null)
    {
        $this->mpdf->AddPage();

        if(!$this->css_written) {
            $this->mpdf->WriteHTML("<style>{$this->css}</style>");
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
