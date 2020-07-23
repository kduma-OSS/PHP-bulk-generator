<?php


namespace Kduma\BulkGenerator\PdfGenerators;


use Kduma\BulkGenerator\PageOptions\PageMargins;
use Kduma\BulkGenerator\PageOptions\PageSize;

class MpdfGenerator implements PdfGeneratorInterface
{
    private \Mpdf\Mpdf $mpdf;
    private array $templates = [];
    /**
     * @var PageSize
     */
    private PageSize $pageSize;
    /**
     * @var PageMargins
     */
    private PageMargins $pageMargins;

    /**
     * MpdfGenerator constructor.
     *
     * @param PageSize    $pageSize
     * @param PageMargins $pageMargins
     */
    public function __construct(PageSize $pageSize = null, PageMargins $pageMargins = null)
    {
        $this->pageSize = $pageSize ?? PageSize::fromName('A4');
        $this->pageMargins = $pageMargins ?? new PageMargins();
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
    }

    public function insert(string $html, string $template = null)
    {
        $this->mpdf->AddPage();
        
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
}