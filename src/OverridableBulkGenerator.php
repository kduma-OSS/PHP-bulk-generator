<?php


namespace Kduma\BulkGenerator;


use Kduma\BulkGenerator\ContentGenerators\ContentGeneratorInterface;
use Kduma\BulkGenerator\DataSources\DataSourceInterface;
use Kduma\BulkGenerator\PdfGenerators\PdfGeneratorInterface;

class OverridableBulkGenerator extends BulkGenerator
{
    /**
     * @var array
     */
    protected array $front_template_overrides = [];

    /**
     * @var array
     */
    protected array $front_content_generator_overrides = [];

    /**
     * @var array
     */
    protected array $back_content_generator_overrides = [];

    /**
     * @var array
     */
    protected array $back_template_overrides = [];

    /**
     * @var string|callable
     */
    protected $template_resolver;

    /**
     * OverridableBulkGenerator constructor.
     *
     * @param DataSourceInterface   $data_source
     * @param PdfGeneratorInterface $pdf_generator
     * @param string|callable       $template_resolver
     */
    public function __construct(DataSourceInterface $data_source, PdfGeneratorInterface $pdf_generator, $template_resolver)
    {
        parent::__construct($data_source, $pdf_generator);
        $this->template_resolver = $template_resolver;
    }


    /**
     * @param array $row
     * @param bool  $has_front_side
     * @param bool  $has_back_side
     */
    protected function renderOne(array $row, bool $has_front_side, bool $has_back_side): void
    {
        $template = $this->getTemplateName($row);

        if ($has_front_side) {
            $front_content_generator = $this->getFrontContentGenerator($template) ?? $this->getFrontContentGenerator();

            $this->pdf_generator->insert(
                $front_content_generator ? $front_content_generator->getContent($row) : '',
                $this->getFrontTemplate($template) ?? $this->getFrontTemplate()
            );
        }

        if ($has_back_side) {
            $back_content_generator = $this->getBackContentGenerator($template) ?? $this->getBackContentGenerator();

            $this->pdf_generator->insert(
                $back_content_generator ? $back_content_generator->getContent($row) : '',
                $this->getBackTemplate($template) ?? $this->getBackTemplate()
            );
        }
    }

    /**
     * @param array $row
     *
     * @return string
     */
    protected function getTemplateName(array $row)
    {
        if(is_string($this->template_resolver))
            return $row[$this->template_resolver];

        $callback = $this->template_resolver;

        return $callback($row);
    }

    /**
     * @param string|null $template
     *
     * @return ContentGeneratorInterface|null
     */
    public function getFrontContentGenerator(string $template = null): ?ContentGeneratorInterface
    {
        if($template === null)
            return parent::getFrontContentGenerator();

        return $this->front_content_generator_overrides[$template] ?? null;
    }

    /**
     * @param string|null $template
     *
     * @return string|null
     */
    public function getFrontTemplate(string $template = null): ?string
    {
        if($template === null)
            return parent::getFrontTemplate();

        return $this->front_template_overrides[$template] ?? null;
    }

    /**
     * @param string|null $template
     *
     * @return ContentGeneratorInterface|null
     */
    public function getBackContentGenerator(string $template = null): ?ContentGeneratorInterface
    {
        if($template === null)
            return parent::getBackContentGenerator();

        return $this->back_content_generator_overrides[$template] ?? null;
    }

    /**
     * @param string|null $template
     *
     * @return string|null
     */
    public function getBackTemplate(string $template = null): ?string
    {
        if($template === null)
            return parent::getBackTemplate();

        return $this->back_template_overrides[$template] ?? null;
    }

    /**
     * @param ContentGeneratorInterface|null $front_content_generator
     * @param string|null                    $template
     *
     * @return $this
     */
    public function setFrontContentGenerator(?ContentGeneratorInterface $front_content_generator, string $template = null): OverridableBulkGenerator
    {
        if($template === null)
            return parent::setFrontContentGenerator($front_content_generator);

        $this->front_content_generator_overrides[$template] = $front_content_generator;
        return $this;
    }

    /**
     * @param string|null $front_template
     * @param string|null $template
     *
     * @return $this
     */
    public function setFrontTemplate(?string $front_template, string $template = null): OverridableBulkGenerator
    {
        if($template === null)
            return parent::setFrontTemplate($front_template);

        $this->front_template_overrides[$template] = $front_template;
        return $this;
    }

    /**
     * @param ContentGeneratorInterface|null $back_content_generator
     * @param string|null                    $template
     *
     * @return $this
     */
    public function setBackContentGenerator(?ContentGeneratorInterface $back_content_generator, string $template = null): OverridableBulkGenerator
    {
        if($template === null)
            return parent::setBackContentGenerator($back_content_generator);

        $this->back_content_generator_overrides[$template] = $back_content_generator;
        return $this;
    }

    /**
     * @param string|null $back_template
     * @param string|null $template
     *
     * @return $this
     */
    public function setBackTemplate(?string $back_template, string $template = null): OverridableBulkGenerator
    {
        if($template === null)
            return parent::setBackTemplate($back_template);

        $this->back_template_overrides[$template] = $back_template;
        return $this;
    }

    /**
     * @return array
     */
    public function getFrontTemplateOverrides(): array
    {
        return $this->front_template_overrides;
    }

    /**
     * @return array
     */
    public function getFrontContentGeneratorOverrides(): array
    {
        return $this->front_content_generator_overrides;
    }

    /**
     * @return array
     */
    public function getBackContentGeneratorOverrides(): array
    {
        return $this->back_content_generator_overrides;
    }

    /**
     * @return array
     */
    public function getBackTemplateOverrides(): array
    {
        return $this->back_template_overrides;
    }

    /**
     * @param array $front_template_overrides
     *
     * @return OverridableBulkGenerator
     */
    public function setFrontTemplateOverrides(array $front_template_overrides): OverridableBulkGenerator
    {
        $this->front_template_overrides = $front_template_overrides;
        return $this;
    }

    /**
     * @param array $front_content_generator_overrides
     *
     * @return OverridableBulkGenerator
     */
    public function setFrontContentGeneratorOverrides(array $front_content_generator_overrides): OverridableBulkGenerator
    {
        $this->front_content_generator_overrides = $front_content_generator_overrides;
        return $this;
    }

    /**
     * @param array $back_content_generator_overrides
     *
     * @return OverridableBulkGenerator
     */
    public function setBackContentGeneratorOverrides(array $back_content_generator_overrides): OverridableBulkGenerator
    {
        $this->back_content_generator_overrides = $back_content_generator_overrides;
        return $this;
    }

    /**
     * @param array $back_template_overrides
     *
     * @return OverridableBulkGenerator
     */
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
     *
     * @return OverridableBulkGenerator
     */
    public function setTemplateResolver($template_resolver): OverridableBulkGenerator
    {
        $this->template_resolver = $template_resolver;
        return $this;
    }
}