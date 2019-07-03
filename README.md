# Chrome2Pdf

Convert HTML to pdf using headless chrome.

```php
use Tesla\Chrome2Pdf\Chrome2Pdf;

$c2p = new Chrome2Pdf();
$c2p->setChromeExecutablePath('/opt/google/chrome/chrome')
    ->appendChromeArgs(['--disable-gpu']);

$pdf = $c2p
    ->portrait()
    ->setPaperFormat('A4')
    ->setMargins(10, 10, 10, 10, 'mm')
    ->setContent('<h1>Hello world</h1><p>This is a paragraph</p>')
    ->setHeader('<div style="font-size: 11px">This is a header</div>')
    ->setFooter('<div style="font-size: 11px">This is a footer <span class="pageNumber"></span>/<span class="totalPages"></span></div>')
    ->pdf();

file_put_contents('test.pdf', $pdf);
```

Show pdf in browser (Laravel example):

```php
<?php
use Tesla\Chrome2Pdf\Chrome2Pdf;

class ExampleController extends Controller
{
    public function showPdf()
    {
        $pdf = (new Chrome2Pdf())
            ->portrait()
            ->setPaperFormat('A4')
            ->setContent('<h1>Hello world</h1><p>This is a paragraph</p>')
            ->pdf();

        return response()->file($pdf);
    }
}
```

## Usage

Install chrome

```bash
wget https://dl.google.com/linux/direct/google-chrome-stable_current_amd64.deb
sudo dpkg -i google-chrome-stable_current_amd64.deb
```

Install package

```bash
composer require tesla-software/chrome2pdf
```

Create Chrome2Pdf instance and give it some content:

```php
$pdfContent = (new \Tesla\Chrome2Pdf\Chrome2Pdf())
    ->setContent('<h1>Hello world</h1><p>This is a paragraph</p>')
    ->pdf();
```

### Change Chrome executable path

```php
$chrome2pdf
    ->setChromeExecutablePath('/custom/path/to/chrome')
    ->setContent('<h1>Hello world</h1><p>This is a paragraph</p>')
    ->pdf();
```

### Change temp directory path

Every time you generate pdf, this package create a temporary .html file with given content. Make sure that given directory path is writable and readable.

```php
$chrome2pdf
    ->setTempFolder('/storage/temp/pdf')
    ->setContent('<h1>Hello world</h1><p>This is a paragraph</p>')
    ->pdf();
```

### Additional Chrome arguments

You can add custom arguments to chrome instance.

```php
$chrome2pdf
    ->appendChromeArgs(['--disable-gpu'])
    ->setContent('<h1>Hello world</h1><p>This is a paragraph</p>')
    ->pdf();
```

### Available pdf options

```php
// Available options: A0, A1, A2, A3, A4, A5, A6, Letter
$chrome2pdf->setPaperFormat('A4');

// Custom paper width and height (inches)
$chrome2pdf->setPaperWidth(8)->setPaperHeight(12);

// Custom margins ($top, $right, $bottom, $left, $measurementUnit)
$chrome2pdf->setMargins(2, 3, 2, 3, 'mm');

// Change paper orientation
$chrome2pdf->portrait();
$chrome2pdf->landscape();

// Change webpage rendering scale
$chrome2pdf->setScale(1);

// Set header and footer HTML
$chrome2pdf->setHeader('<p>Header text</p>');
$chrome2pdf->setFooter('<p>Footer text</p>');

// Set pdf body content
$chrome2pdf->setContent('<p>Demo content</p>');

// Give any CSS @page size declared in the page priority over what is declared
// in width and height or format options
$chrome2pdf->setPreferCSSPageSize(true);

// Print background graphics
$chrome2pdf->setPrintBackground(true);
```

## Misc

chrome template: https://cs.chromium.org/chromium/src/components/printing/resources/print_header_footer_template_page.html
