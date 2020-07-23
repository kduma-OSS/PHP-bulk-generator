# Bulk Document Generator

[![Latest Version on Packagist](https://img.shields.io/packagist/v/kduma/bulk-generator.svg?style=flat-square)](https://packagist.org/packages/kduma/bulk-generator)
[![Build Status](https://img.shields.io/travis/kduma/bulk-generator/master.svg?style=flat-square)](https://travis-ci.org/kduma/bulk-generator)
[![Quality Score](https://img.shields.io/scrutinizer/g/kduma/bulk-generator.svg?style=flat-square)](https://scrutinizer-ci.com/g/kduma/bulk-generator)
[![Total Downloads](https://img.shields.io/packagist/dt/kduma/bulk-generator.svg?style=flat-square)](https://packagist.org/packages/kduma/bulk-generator)

Bulk Document Generator

## Installation

You can install the package via composer:

```bash
composer require kduma/bulk-generator
```

## Usage

```php
use Kduma\BulkGenerator\ContentGenerators\SimpleTemplateWithPlaceholdersContentGenerator;
use Kduma\BulkGenerator\DataSources\CsvWithHeadersDataSource;
use Kduma\BulkGenerator\PdfGenerators\MpdfGenerator;
use Kduma\BulkGenerator\PageOptions\PageMargins;
use Kduma\BulkGenerator\PageOptions\PageSize;
use Kduma\BulkGenerator\BulkGenerator;

$dataSource = new CsvWithHeadersDataSource('data.csv');

$pdfGenerator = new MpdfGenerator(
    PageSize::fromName('A6', true),
    PageMargins::makeByAxis(5, 5)
);

$generator = (new BulkGenerator($dataSource, $pdfGenerator))
    ->setFrontTemplate('front_template.pdf')
    ->setFrontContentGenerator(new SimpleTemplateWithPlaceholdersContentGenerator('Hello {name}!'));

$generator->generate('output.pdf');
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email git@krystian.duma.sh instead of using the issue tracker.

## Credits

- [Krystian Duma](https://github.com/kduma)
- [All Contributors](../../contributors)

## License

The GNU GPLv3. Please see [License File](LICENSE.md) for more information.