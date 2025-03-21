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
     * @param DataSourceInterface $dataSource
     * @param PdfGeneratorInterface $pdfGenerator
     */
    public function __construct(protected DataSourceInterface $dataSource, protected PdfGeneratorInterface $pdfGenerator)
    {
    }


    public function generate(string $filename)
    {
        $data = $this->dataSource->getData();

        if(count($data) == 0)
            throw new \Exception("Data source is empty!");

        if(!$this->front_content_generator && !$this->front_template && !$this->back_template && !$this->back_content_generator)
            throw new \Exception("No templates provided to generate!");

        $has_front_side = $this->front_content_generator instanceof \Kduma\BulkGenerator\ContentGenerators\ContentGeneratorInterface || $this->front_template;
        $has_back_side = $this->back_template || $this->back_content_generator instanceof \Kduma\BulkGenerator\ContentGenerators\ContentGeneratorInterface;
        $this->pdfGenerator->start($has_front_side && $has_back_side);

        foreach ($data as $row) {
            $this->renderOne($row, $has_front_side, $has_back_side);
        }

        file_put_contents($filename, $this->pdfGenerator->finish());
    }

    /**
     * @param array $row
     * @param bool  $has_front_side
     * @param bool  $has_back_side
     */
    protected function renderOne(array $row, bool $has_front_side, bool $has_back_side): void
    {
        if ($has_front_side)
            $this->pdfGenerator->insert(
                $this->front_content_generator ? $this->front_content_generator->getContent($row) : '',
                $this->front_template
            );

        if ($has_back_side)
            $this->pdfGenerator->insert(
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
     * @param ContentGeneratorInterface|null $contentGenerator
     *
     * @return BulkGenerator
     */
    public function setFrontContentGenerator(?ContentGeneratorInterface $contentGenerator): BulkGenerator
    {
        $this->front_content_generator = $contentGenerator;
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
     * @param ContentGeneratorInterface|null $contentGenerator
     *
     * @return BulkGenerator
     */
    public function setBackContentGenerator(?ContentGeneratorInterface $contentGenerator): BulkGenerator
    {
        $this->back_content_generator = $contentGenerator;
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
        return $this->dataSource;
    }

    /**
     * @param DataSourceInterface $dataSource
     *
     * @return BulkGenerator
     */
    public function setDataSource(DataSourceInterface $dataSource): BulkGenerator
    {
        $this->dataSource = $dataSource;
        return $this;
    }

    /**
     * @return PdfGeneratorInterface
     */
    public function getPdfGenerator(): PdfGeneratorInterface
    {
        return $this->pdfGenerator;
    }

    /**
     * @param PdfGeneratorInterface $pdfGenerator
     *
     * @return BulkGenerator
     */
    public function setPdfGenerator(PdfGeneratorInterface $pdfGenerator): BulkGenerator
    {
        $this->pdfGenerator = $pdfGenerator;
        return $this;
    }
}
