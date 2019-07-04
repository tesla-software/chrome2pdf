<?php
require __DIR__.'/../vendor/autoload.php';

use Tesla\Chrome2Pdf\Chrome2Pdf;

$c2p = (new Chrome2Pdf())->setChromeExecutablePath('/opt/google/chrome/chrome');

// Default example
$example1Start = microtime(true);
$pdf = $c2p
    ->appendChromeArgs(['--disable-gpu'])
    ->setWaitForLifecycleEvent('networkIdle')
    ->setContent(file_get_contents('example1.html'))
    ->setPaperFormat('A4')
    ->setMargins(5, 5, 10, 5, 'mm')
    ->setHeader(file_get_contents('header.html'))
    ->setFooter(file_get_contents('footer.html'))
    ->pdf();

file_put_contents('example1.pdf', $pdf);

echo('Example 1 took ' . number_format((microtime(true) - $example1Start), 2) . 'µs');
echo("\n");

// Invoice example (https://github.com/sparksuite/simple-html-invoice-template)
$c2p = (new Chrome2Pdf())->setChromeExecutablePath('/opt/google/chrome/chrome');
$example2Start = microtime(true);
$pdf = $c2p
    ->setContent(file_get_contents('invoice.html'))
    ->setPaperFormat('A4')
    ->setMargins(11, 7, 11, 7, 'mm')
    ->pdf();

file_put_contents('invoice.pdf', $pdf);

echo('Invoice example took ' . number_format((microtime(true) - $example2Start), 2) . 'µs');
echo("\n");
