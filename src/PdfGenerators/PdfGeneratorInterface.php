<?php


namespace Kduma\BulkGenerator\PdfGenerators;


interface PdfGeneratorInterface
{
    public function start(bool $doubleSided);
    public function insert(string $html, string $template = null);
    public function finish(): string;
}