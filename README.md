# tesla-software/chrome2pdf

Convert HTML to pdf using Headless chrome

```php
use Tesla\Chrome2Pdf\Chrome2Pdf;

$c2p = new Chrome2Pdf();
$c2p->setChromeExecutablePath('/opt/google/chrome/chrome');

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

## Setup

Install chrome

```bash
wget https://dl.google.com/linux/direct/google-chrome-stable_current_amd64.deb
sudo dpkg -i google-chrome-stable_current_amd64.deb
```

Install package

```bash
composer require tesla-software/chrome2pdf
```

## Misc

chrome template: https://cs.chromium.org/chromium/src/components/printing/resources/print_header_footer_template_page.html
