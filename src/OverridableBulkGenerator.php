<?php

declare(strict_types=1);

namespace Kduma\BulkGenerator;


use Kduma\BulkGenerator\ContentGenerators\ContentGeneratorInterface;
use Kduma\BulkGenerator\DataSources\DataSourceInterface;
use Kduma\BulkGenerator\PdfGenerators\PdfGeneratorInterface;

class OverridableBulkGenerator extends BulkGenerator
{
    protected array $front_template_overrides = [];

    protected array $front_content_generator_overrides = [];

    protected array $back_content_generator_overrides = [];

    protected array $back_template_overrides = [];

    /**
     * @var string|callable
     */
    protected $template_resolver;

    /**
     * OverridableBulkGenerator constructor.
     *
     * @param string|callable       $template_resolver
     */
    public function __construct(DataSourceInterface $dataSource, PdfGeneratorInterface $pdfGenerator, $template_resolver)
    {
        parent::__construct($dataSource, $pdfGenerator);
        $this->template_resolver = $template_resolver;
    }


    protected function renderOne(array $row, bool $has_front_side, bool $has_back_side): void
    {
        $template = $this->getTemplateName($row);

        if ($has_front_side) {
            $front_content_generator = $this->getFrontContentGenerator($template) ?? $this->getFrontContentGenerator();

            $this->pdfGenerator->insert(
                $front_content_generator instanceof \Kduma\BulkGenerator\ContentGenerators\ContentGeneratorInterface ? $front_content_generator->getContent($row) : '',
                $this->getFrontTemplate($template) ?? $this->getFrontTemplate()
            );
        }

        if ($has_back_side) {
            $back_content_generator = $this->getBackContentGenerator($template) ?? $this->getBackContentGenerator();

            $this->pdfGenerator->insert(
                $back_content_generator instanceof \Kduma\BulkGenerator\ContentGenerators\ContentGeneratorInterface ? $back_content_generator->getContent($row) : '',
                $this->getBackTemplate($template) ?? $this->getBackTemplate()
            );
        }
    }

    /**
     * @return string
     */
    protected function getTemplateName(array $row)
    {
        if (is_string($this->template_resolver)) {
            return $row[$this->template_resolver];
        }

        $callback = $this->template_resolver;

        return $callback($row);
    }

    /**
     * @param string|null $template
     */
    public function getFrontContentGenerator(string $template = null): ?ContentGeneratorInterface
    {
        if ($template === null) {
            return parent::getFrontContentGenerator();
        }

        return $this->front_content_generator_overrides[$template] ?? null;
    }

    /**
     * @param string|null $template
     */
    public function getFrontTemplate(string $template = null): ?string
    {
        if ($template === null) {
            return parent::getFrontTemplate();
        }

        return $this->front_template_overrides[$template] ?? null;
    }

    /**
     * @param string|null $template
     */
    public function getBackContentGenerator(string $template = null): ?ContentGeneratorInterface
    {
        if ($template === null) {
            return parent::getBackContentGenerator();
        }

        return $this->back_content_generator_overrides[$template] ?? null;
    }

    /**
     * @param string|null $template
     */
    public function getBackTemplate(string $template = null): ?string
    {
        if ($template === null) {
            return parent::getBackTemplate();
        }

        return $this->back_template_overrides[$template] ?? null;
    }

    /**
     * @param string|null                    $template
     *
     */
    public function setFrontContentGenerator(?ContentGeneratorInterface $contentGenerator, string $template = null): OverridableBulkGenerator
    {
        if ($template === null) {
            return parent::setFrontContentGenerator($contentGenerator);
        }

        $this->front_content_generator_overrides[$template] = $contentGenerator;
        return $this;
    }

    /**
     * @param string|null $template
     *
     */
    public function setFrontTemplate(?string $front_template, string $template = null): OverridableBulkGenerator
    {
        if ($template === null) {
            return parent::setFrontTemplate($front_template);
        }

        $this->front_template_overrides[$template] = $front_template;
        return $this;
    }

    /**
     * @param string|null                    $template
     *
     */
    public function setBackContentGenerator(?ContentGeneratorInterface $contentGenerator, string $template = null): OverridableBulkGenerator
    {
        if ($template === null) {
            return parent::setBackContentGenerator($contentGenerator);
        }

        $this->back_content_generator_overrides[$template] = $contentGenerator;
        return $this;
    }

    /**
     * @param string|null $template
     *
     */
    public function setBackTemplate(?string $back_template, string $template = null): OverridableBulkGenerator
    {
        if ($template === null) {
            return parent::setBackTemplate($back_template);
        }

        $this->back_template_overrides[$template] = $back_template;
        return $this;
    }

    public function getFrontTemplateOverrides(): array
    {
        return $this->front_template_overrides;
    }

    public function getFrontContentGeneratorOverrides(): array
    {
        return $this->front_content_generator_overrides;
    }

    public function getBackContentGeneratorOverrides(): array
    {
        return $this->back_content_generator_overrides;
    }

    public function getBackTemplateOverrides(): array
    {
        return $this->back_template_overrides;
    }

    public function setFrontTemplateOverrides(array $front_template_overrides): OverridableBulkGenerator
    {
        $this->front_template_overrides = $front_template_overrides;
        return $this;
    }

    public function setFrontContentGeneratorOverrides(array $front_content_generator_overrides): OverridableBulkGenerator
    {
        $this->front_content_generator_overrides = $front_content_generator_overrides;
        return $this;
    }

    public function setBackContentGeneratorOverrides(array $back_content_generator_overrides): OverridableBulkGenerator
    {
        $this->back_content_generator_overrides = $back_content_generator_overrides;
        return $this;
    }

    public function setBackTemplateOverrides(array $back_template_overrides): OverridableBulkGenerator
    {
        $this->back_template_overrides = $back_template_overrides;
        return $this;
    }

    /**
     * @return string|callable
     */
    public function getTemplateResolver()
    {
        return $this->template_resolver;
    }

    /**
     * @param string|callable $template_resolver
     */
    public function setTemplateResolver($template_resolver): OverridableBulkGenerator
    {
        $this->template_resolver = $template_resolver;
        return $this;
    }
}
