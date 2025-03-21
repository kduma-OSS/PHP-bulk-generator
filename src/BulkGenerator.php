<?php

namespace Kduma\BulkGenerator;

use Kduma\BulkGenerator\ContentGenerators\ContentGeneratorInterface;
use Kduma\BulkGenerator\DataSources\DataSourceInterface;
use Kduma\BulkGenerator\PdfGenerators\PdfGeneratorInterface;

class BulkGenerator
{
    protected ?ContentGeneratorInterface $front_content_generator = null;
    protected ?string $front_template = null;
    
    protected ?ContentGeneratorInterface $back_content_generator = null;
    protected ?string $back_template = null;

    /**
     * BulkGenerator constructor.
     *
     * @param DataSourceInterface   $data_source
     * @param PdfGeneratorInterface $pdf_generator
     */
    public function __construct(protected DataSourceInterface $data_source, protected PdfGeneratorInterface $pdf_generator)
    {
    }


    public function generate(string $filename)
    {
        $data = $this->data_source->getData();
        
        if(count($data) == 0)
            throw new \Exception("Data source is empty!");
        
        if(!$this->front_content_generator && !$this->front_template && !$this->back_template && !$this->back_content_generator)
            throw new \Exception("No templates provided to generate!");

        $has_front_side = $this->front_content_generator || $this->front_template;
        $has_back_side = $this->back_template || $this->back_content_generator;
        $this->pdf_generator->start($has_front_side && $has_back_side);

        foreach ($data as $row) {
            $this->renderOne($row, $has_front_side, $has_back_side);
        }
        
        file_put_contents($filename, $this->pdf_generator->finish());
    }
    
    /**
     * @param array $row
     * @param bool  $has_front_side
     * @param bool  $has_back_side
     */
    protected function renderOne(array $row, bool $has_front_side, bool $has_back_side): void
    {
        if ($has_front_side)
            $this->pdf_generator->insert(
                $this->front_content_generator ? $this->front_content_generator->getContent($row) : '',
                $this->front_template
            );

        if ($has_back_side)
            $this->pdf_generator->insert(
                $this->back_content_generator ? $this->back_content_generator->getContent($row) : '',
                $this->back_template
            );
    }

    /**
     * @return ContentGeneratorInterface|null
     */
    public function getFrontContentGenerator(): ?ContentGeneratorInterface
    {
        return $this->front_content_generator;
    }

    /**
     * @param ContentGeneratorInterface|null $front_content_generator
     *
     * @return BulkGenerator
     */
    public function setFrontContentGenerator(?ContentGeneratorInterface $front_content_generator): BulkGenerator
    {
        $this->front_content_generator = $front_content_generator;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFrontTemplate(): ?string
    {
        return $this->front_template;
    }

    /**
     * @param string|null $front_template
     *
     * @return BulkGenerator
     */
    public function setFrontTemplate(?string $front_template): BulkGenerator
    {
        $this->front_template = $front_template;
        return $this;
    }

    /**
     * @return ContentGeneratorInterface|null
     */
    public function getBackContentGenerator(): ?ContentGeneratorInterface
    {
        return $this->back_content_generator;
    }

    /**
     * @param ContentGeneratorInterface|null $back_content_generator
     *
     * @return BulkGenerator
     */
    public function setBackContentGenerator(?ContentGeneratorInterface $back_content_generator): BulkGenerator
    {
        $this->back_content_generator = $back_content_generator;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getBackTemplate(): ?string
    {
        return $this->back_template;
    }

    /**
     * @param string|null $back_template
     *
     * @return BulkGenerator
     */
    public function setBackTemplate(?string $back_template): BulkGenerator
    {
        $this->back_template = $back_template;
        return $this;
    }

    /**
     * @return DataSourceInterface
     */
    public function getDataSource(): DataSourceInterface
    {
        return $this->data_source;
    }

    /**
     * @param DataSourceInterface $data_source
     *
     * @return BulkGenerator
     */
    public function setDataSource(DataSourceInterface $data_source): BulkGenerator
    {
        $this->data_source = $data_source;
        return $this;
    }

    /**
     * @return PdfGeneratorInterface
     */
    public function getPdfGenerator(): PdfGeneratorInterface
    {
        return $this->pdf_generator;
    }

    /**
     * @param PdfGeneratorInterface $pdf_generator
     *
     * @return BulkGenerator
     */
    public function setPdfGenerator(PdfGeneratorInterface $pdf_generator): BulkGenerator
    {
        $this->pdf_generator = $pdf_generator;
        return $this;
    }
}
