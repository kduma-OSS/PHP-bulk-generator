<?php

declare(strict_types=1);

namespace Kduma\BulkGenerator;

use Exception;
use Kduma\BulkGenerator\ContentGenerators\ContentGeneratorInterface;
use Kduma\BulkGenerator\DataSources\DataSourceInterface;
use Kduma\BulkGenerator\PdfGenerators\PdfGeneratorInterface;

class BulkGenerator
{
    protected ?ContentGeneratorInterface $front_content_generator = null;

    protected ?string $front_template = null;

    protected ?ContentGeneratorInterface $back_content_generator = null;

    protected ?string $back_template = null;

    public function __construct(
        protected DataSourceInterface $dataSource,
        protected PdfGeneratorInterface $pdfGenerator
    ) {
    }

    public function generate(string $filename): void
    {
        $data = $this->dataSource->getData();

        if ($data === []) {
            throw new Exception('Data source is empty!');
        }

        if (! $this->front_content_generator && ! $this->front_template && ! $this->back_template && ! $this->back_content_generator) {
            throw new Exception('No templates provided to generate!');
        }

        $has_front_side = $this->front_content_generator instanceof ContentGeneratorInterface || $this->front_template;
        $has_back_side = $this->back_template || $this->back_content_generator instanceof ContentGeneratorInterface;
        $this->pdfGenerator->start($has_front_side && $has_back_side);

        foreach ($data as $row) {
            $this->renderOne($row, $has_front_side, $has_back_side);
        }

        file_put_contents($filename, $this->pdfGenerator->finish());
    }

    public function getFrontContentGenerator(): ?ContentGeneratorInterface
    {
        return $this->front_content_generator;
    }

    public function setFrontContentGenerator(?ContentGeneratorInterface $contentGenerator): self
    {
        $this->front_content_generator = $contentGenerator;
        return $this;
    }

    public function getFrontTemplate(): ?string
    {
        return $this->front_template;
    }

    public function setFrontTemplate(?string $front_template): self
    {
        $this->front_template = $front_template;
        return $this;
    }

    public function getBackContentGenerator(): ?ContentGeneratorInterface
    {
        return $this->back_content_generator;
    }

    public function setBackContentGenerator(?ContentGeneratorInterface $contentGenerator): self
    {
        $this->back_content_generator = $contentGenerator;
        return $this;
    }

    public function getBackTemplate(): ?string
    {
        return $this->back_template;
    }

    public function setBackTemplate(?string $back_template): self
    {
        $this->back_template = $back_template;
        return $this;
    }

    public function getDataSource(): DataSourceInterface
    {
        return $this->dataSource;
    }

    public function setDataSource(DataSourceInterface $dataSource): self
    {
        $this->dataSource = $dataSource;
        return $this;
    }

    public function getPdfGenerator(): PdfGeneratorInterface
    {
        return $this->pdfGenerator;
    }

    public function setPdfGenerator(PdfGeneratorInterface $pdfGenerator): self
    {
        $this->pdfGenerator = $pdfGenerator;
        return $this;
    }

    protected function renderOne(array $row, bool $has_front_side, bool $has_back_side): void
    {
        if ($has_front_side) {
            $this->pdfGenerator->insert(
                $this->front_content_generator instanceof ContentGeneratorInterface ? $this->front_content_generator->getContent(
                    $row
                ) : '',
                $this->front_template
            );
        }

        if ($has_back_side) {
            $this->pdfGenerator->insert(
                $this->back_content_generator instanceof ContentGeneratorInterface ? $this->back_content_generator->getContent(
                    $row
                ) : '',
                $this->back_template
            );
        }
    }
}
