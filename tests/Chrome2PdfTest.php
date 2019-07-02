<?php
namespace Tesla\Chrome2Pdf;

class Chrome2PdfTest extends TestCase
{
    /** @test */
    public function it_generates_pdf_with_content()
    {
        $pdf = (new Chrome2Pdf())->setContent('<h1>Test</h1>')->pdf();

        $this->assertNotNull($pdf);
    }

    /** @test */
    public function it_cant_generate_pdf_without_content()
    {
        $this->expectException(\InvalidArgumentException::class);

        (new Chrome2Pdf())->pdf();
    }

    /** @test */
    public function it_can_change_temp_directory()
    {
        $pdf = (new Chrome2Pdf())
            ->setTempFolder(__DIR__ . '/temp/')
            ->setContent('<h1>Test</h1>')
            ->pdf();

        $this->assertNotNull($pdf);
    }
}
