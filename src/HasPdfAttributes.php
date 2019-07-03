<?php
declare(strict_types=1);

namespace Tesla\Chrome2Pdf;

trait HasPdfAttributes
{
    /**
     * Pdf content
     *
     * @var string
     */
    private $content;

    /**
     * Print background graphics
     *
     * @var bool
     */
    private $printBackground = false;

    /**
     * Give any CSS @page size declared in the page priority over what is declared
     * in width and height or format options.
     * Defaults to false, which will scale the content to fit the paper size.
     *
     * @var bool
     */
    private $preferCSSPageSize = false;

    /**
     * Paper orientation
     *
     * @var string
     */
    private $orientation = 'portrait';

    /**
     * HTML template for the print header. Should be valid HTML markup.
     * Script tags inside templates are not evaluated.
     * Page styles are not visible inside templates.
     *
     * @var string|null
     */
    private $header = null;

    /**
     * HTML template for the print footer. Should be valid HTML markup.
     * Script tags inside templates are not evaluated.
     * Page styles are not visible inside templates.
     *
     * @var string|null
     */
    private $footer = null;

    /**
     * Paper width in inches
     *
     * @var float
     */
    private $paperWidth = 8.27;

    /**
     * Paper height in inches
     *
     * @var float
     */
    private $paperHeight = 11.7;

    /**
     * Page margins in inches
     *
     * @var array
     */
    private $margins = [
        'top' => 0.4,
        'right' => 0.4,
        'bottom' => 0.4,
        'left' => 0.4,
    ];

    /**
     * Default paper formats
     *
     * @var array
     */
    private $paperFormats = [
        'Letter' => [8.5, 11],
        'A0' => [33.1, 46.8],
        'A1' => [23.4, 33.1],
        'A2' => [16.54, 23.4],
        'A3' => [11.7, 16.54],
        'A4' => [8.27, 11.7],
        'A5' => [5.83, 8.27],
        'A6' => [4.13, 5.83],
    ];

    /**
     * Scale of the webpage rendering.
     * Scale amount must be between 0.1 and 2.
     *
     * @var int|float
     */
    private $scale = 1;

    public function setPaperFormat(string $format): Chrome2Pdf
    {
        if (!array_key_exists($format, $this->paperFormats)) {
            throw new InvalidArgumentException('Paper format "' . $format . '" does not exist');
        }

        $this->paperWidth = $this->paperFormats[$format][0];
        $this->paperHeight = $this->paperFormats[$format][1];

        return $this;
    }

    public function portrait(): Chrome2Pdf
    {
        $this->orientation = 'portrait';

        return $this;
    }

    public function landscape(): Chrome2Pdf
    {
        $this->orientation = 'landscape';

        return $this;
    }

    public function setMargins(float $top, float $right, float $bottom, float $left, string $unit = 'inch'): Chrome2Pdf
    {
        if ($unit === 'mm') {
            $top = $top * 0.03937;
            $right = $right * 0.03937;
            $bottom = $bottom * 0.03937;
            $left = $left * 0.03937;
        }

        $this->margins['top'] = $top;
        $this->margins['right'] = $right;
        $this->margins['bottom'] = $bottom;
        $this->margins['left'] = $left;

        return $this;
    }

    public function setContent(string $content): Chrome2Pdf
    {
        $this->content = $content;

        return $this;
    }

    public function setHeader(?string $header): Chrome2Pdf
    {
        $this->header = $header;

        return $this;
    }

    public function setFooter(?string $footer): Chrome2Pdf
    {
        $this->footer = $footer;

        return $this;
    }

    public function setPreferCSSPageSize(bool $preferCss): Chrome2Pdf
    {
        $this->preferCSSPageSize = $preferCss;

        return $this;
    }

    public function setPaperWidth(float $width): Chrome2Pdf
    {
        $this->paperWidth = $width;

        return $this;
    }

    public function setPaperHeight(float $height): Chrome2Pdf
    {
        $this->paperHeight = $height;

        return $this;
    }

    public function setScale($scale): Chrome2Pdf
    {
        $this->scale = $scale;

        return $this;
    }

    public function setPrintBackground(bool $printBg): Chrome2Pdf
    {
        $this->printBackground = $printBg;

        return $this;
    }
}
