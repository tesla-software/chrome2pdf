<?php
declare(strict_types=1);

namespace Tesla\Chrome2Pdf;

use RuntimeException;
use InvalidArgumentException;
use ChromeDevtoolsProtocol\Context;
use ChromeDevtoolsProtocol\ContextInterface;
use ChromeDevtoolsProtocol\Instance\Launcher;
use ChromeDevtoolsProtocol\Model\Page\NavigateRequest;
use ChromeDevtoolsProtocol\Model\Page\PrintToPDFRequest;

class Chrome2Pdf
{
    use HasPdfAttributes;

    /**
     * Context for operations
     *
     * @var ContextInterface
     */
    private $ctx;

    /**
     * Chrome launcher
     *
     * @var Launcher
     */
    private $launcher;

    /**
     * Path to temporary html files
     *
     * @var string|null
     */
    private $tmpFolderPath = null;

    /**
     * Path to Chrome binary
     *
     * @var string
     */
    private $chromeExecutablePath = '/opt/google/chrome/chrome';

    /**
     * Additional Chrome command line arguments
     *
     * @var array
     */
    private $chromeArgs = [];

    public function __construct()
    {
        $this->ctx = Context::withTimeout(Context::background(), 30);
        $this->launcher = new Launcher();
    }

    public function setTempFolder(string $path): Chrome2Pdf
    {
        $this->tmpFolderPath = $path;

        return $this;
    }

    public function getTempFolder(): string
    {
        if ($this->tmpFolderPath === null) {
            return sys_get_temp_dir();
        }

        return $this->tmpFolderPath;
    }

    public function getBrowserLauncher(): Launcher
    {
        return $this->launcher;
    }

    public function setBrowserLauncher(Launcher $launcher): Chrome2Pdf
    {
        $this->launcher = $launcher;

        return $this;
    }

    public function getContext(): ContextInterface
    {
        return $this->ctx;
    }

    public function setContext(ContextInterface $ctx): Chrome2Pdf
    {
        $this->ctx = $ctx;

        return $this;
    }

    public function appendChromeArgs(array $args): Chrome2Pdf
    {
        $this->chromeArgs = array_unique(array_merge($this->chromeArgs, $args));

        return $this;
    }

    public function getChromeExecutablePath(): string
    {
        return $this->chromeExecutablePath;
    }

    public function setChromeExecutablePath(string $chromeExecutablePath): Chrome2Pdf
    {
        $this->chromeExecutablePath = $chromeExecutablePath;

        return $this;
    }

    /**
     * Generate PDF
     *
     * @return string|null
     */
    public function pdf(): ?string
    {
        if (!$this->content) {
            throw new InvalidArgumentException('Missing content, set content by calling "setContent($html)" method');
        }

        $launcher = $this->getBrowserLauncher();
        $launcher->setExecutable($this->getChromeExecutablePath());
        $ctx = $this->getContext();
        $instance = $launcher->launch($ctx, ...$this->chromeArgs);

        $filename = $this->writeTempFile();
        $pdfOptions = $this->getPDFOptions();

        $pdfResult = null;

        try {
            $tab = $instance->open($ctx);
            $tab->activate($ctx);

            $devtools = $tab->devtools();
            try {
                $devtools->page()->enable($ctx);
                $devtools->page()->navigate($ctx, NavigateRequest::builder()->setUrl('file://' . $filename)->build());
                $devtools->page()->awaitLoadEventFired($ctx);

                $response = $devtools->page()->printToPDF($ctx, $pdfOptions);

                $pdfResult = base64_decode($response->data);
            } finally {
                $devtools->close();
            }
        } finally {
            $instance->close();
        }

        $this->deleteTempFile($filename);

        return $pdfResult;
    }

    /**
     * Write content to temporary html file
     *
     * @return string
     */
    protected function writeTempFile(): string
    {
        $filepath = rtrim($this->getTempFolder(), DIRECTORY_SEPARATOR);

        if (!is_dir($filepath)) {
            if (false === @mkdir($filepath, 0777, true) && !is_dir($filepath)) {
                throw new RuntimeException(sprintf("Unable to create directory: %s\n", $filepath));
            }
        } elseif (!is_writable($filepath)) {
            throw new RuntimeException(sprintf("Unable to write in directory: %s\n", $filepath));
        }

        $filename = $filepath . DIRECTORY_SEPARATOR . uniqid('chrome2pdf_', true) . '.html';

        file_put_contents($filename, $this->content);

        return $filename;
    }

    /**
     * Delete temporary file
     *
     * @param string $filename
     * @return void
     */
    protected function deleteTempFile(string $filename): void
    {
        if (file_exists($filename)) {
            unlink($filename);
        }
    }

    /**
     * Populate PDF options
     *
     * @return array
     */
    private function getPDFOptions(): PrintToPDFRequest
    {
        $pdfOptions = PrintToPDFRequest::make();

        $pdfOptions->landscape = $this->orientation === 'landscape';
        $pdfOptions->marginTop = $this->margins['top'];
        $pdfOptions->marginRight = $this->margins['right'];
        $pdfOptions->marginBottom = $this->margins['bottom'];
        $pdfOptions->marginLeft = $this->margins['left'];
        $pdfOptions->preferCSSPageSize = $this->preferCSSPageSize;
        $pdfOptions->printBackground = $this->printBackground;
        $pdfOptions->scale = $this->scale;

        if ($this->paperWidth) {
            $pdfOptions->paperWidth = $this->paperWidth;
        }

        if ($this->paperHeight) {
            $pdfOptions->paperHeight = $this->paperHeight;
        }

        if ($this->header || $this->footer) {
            if ($this->header === null) {
                $this->header = '<p></p>';
            }

            if ($this->footer === null) {
                $this->footer = '<p></p>';
            }

            $pdfOptions->displayHeaderFooter = true;
            $pdfOptions->headerTemplate = $this->header;
            $pdfOptions->footerTemplate = $this->footer;
        }

        return $pdfOptions;
    }
}
