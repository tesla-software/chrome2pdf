<?php
namespace Tesla\Chrome2Pdf;

use Smalot\PdfParser\Parser;

class Chrome2PdfTest extends TestCase
{
    public function setUp(): void
    {
        $this->emptyTempDirectory();
    }

    /** @test */
    public function it_generates_pdf_with_content()
    {
        $pdf = (new Chrome2Pdf())->setChromeExecutablePath('google-chrome-stable')->setContent('<h1>Test</h1>')->pdf();
        $filename = __DIR__ . '/temp/test.pdf';

        file_put_contents($filename, $pdf);

        $parser = new Parser();
        $createdPdf = $parser->parseFile($filename);

        $this->assertNotNull($pdf);
        $this->assertEquals('Test', $createdPdf->getText());
        $this->assertCount(1, $createdPdf->getPages());
    }

    /** @test */
    public function it_cant_generate_pdf_without_content()
    {
        $this->expectException(\InvalidArgumentException::class);

        (new Chrome2Pdf())->setChromeExecutablePath('google-chrome-stable')->pdf();
    }

    /** @test */
    public function it_can_change_temp_directory()
    {
        $pdf = (new Chrome2Pdf())
            ->setChromeExecutablePath('google-chrome-stable')
            ->setTempFolder(__DIR__ . '/temp/')
            ->setContent('<h1>Test</h1>')
            ->pdf();

        $this->assertNotNull($pdf);
    }
}
