<?php

namespace Kduma\BulkGenerator;

use Kduma\BulkGenerator\ContentGenerators\ContentGeneratorInterface;
use Kduma\BulkGenerator\DataSources\DataSourceInterface;
use Kduma\BulkGenerator\PdfGenerators\PdfGeneratorInterface;

class BulkGenerator
{
    public ?ContentGeneratorInterface $front_content_generator = null;
    public ?string $front_template = null;
    
    public ?ContentGeneratorInterface $back_content_generator = null;
    public ?string $back_template = null;
    
    public DataSourceInterface $data_source;
    
    public PdfGeneratorInterface $pdf_generator;

    /**
     * BulkGenerator constructor.
     *
     * @param DataSourceInterface   $data_source
     * @param PdfGeneratorInterface $pdf_generator
     */
    public function __construct(DataSourceInterface $data_source, PdfGeneratorInterface $pdf_generator)
    {
        $this->data_source = $data_source;
        $this->pdf_generator = $pdf_generator;
    }


    public function generate(string $filename)
    {
        $data = $this->data_source->getData();
        
        if(!$data->count())
            throw new \Exception("Data source is empty!");
        
        if(!$this->front_content_generator && !$this->front_template && !$this->back_template && !$this->back_content_generator)
            throw new \Exception("No templates provided to generate!");
        
        $this->pdf_generator->start(($this->front_content_generator || $this->front_template)) && ($this->back_template || $this->back_content_generator);

        $data->each(function ($row) {
            if($this->front_content_generator || $this->front_template)
                $this->pdf_generator->insert(
                    $this->front_content_generator ? $this->front_content_generator->getContent($row) : '',
                    $this->front_template
                );
            
            if($this->back_template || $this->back_content_generator)
                $this->pdf_generator->insert(
                    $this->back_content_generator ? $this->back_content_generator->getContent($row) : '',
                    $this->back_template
                );
        });
        
        file_put_contents($filename, $this->pdf_generator->finish());
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
     * @return DataSourceInterface|null
     */
    public function getDataSource(): ?DataSourceInterface
    {
        return $this->data_source;
    }

    /**
     * @param DataSourceInterface|null $data_source
     *
     * @return BulkGenerator
     */
    public function setDataSource(?DataSourceInterface $data_source): BulkGenerator
    {
        $this->data_source = $data_source;
        return $this;
    }
    
    /**
     * @return PdfGeneratorInterface|null
     */
    public function getPdfGenerator(): ?PdfGeneratorInterface
    {
        return $this->pdf_generator;
    }

    /**
     * @param PdfGeneratorInterface|null $pdf_generator
     *
     * @return BulkGenerator
     */
    public function setPdfGenerator(?PdfGeneratorInterface $pdf_generator): BulkGenerator
    {
        $this->pdf_generator = $pdf_generator;
        return $this;
    }
}
