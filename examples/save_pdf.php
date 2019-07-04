<?php
require __DIR__.'/../vendor/autoload.php';

use Tesla\Chrome2Pdf\Chrome2Pdf;

$content = file_get_contents('demo_content.html');

$c2p = new Chrome2Pdf();

$pdf = $c2p
    ->appendChromeArgs(['--disable-gpu'])
    ->setContent($content)
    ->setPaperFormat('A4')
    ->setMargins(5, 5, 5, 5, 'mm')
    // ->setHeader('<style>#header { padding: 0 !important; }</style><span style="font-size: 12px">Header <span class="date"></span></span>')
    // ->setFooter('<style>#footer { padding: 0 !important; }</style><span style="font-size: 12px">Footer <span class="pageNumber"></span>/<span class="totalPages"></span></span>')
    ->pdf();

file_put_contents('save_pdf_result.pdf', $pdf);
