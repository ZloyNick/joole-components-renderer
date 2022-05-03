<?php

declare(strict_types=1);

namespace joole\components\renderer;

use joole\framework\assets\ResourceInterface;
use joole\framework\content\renderer\RendererInterface;
use joole\framework\content\renderer\view\ViewInterface;
use joole\framework\exception\view\ViewException;

use function extract;
use function filesize;
use function fopen;
use function fread;
use function implode;
use function is_file;
use function ob_end_clean;
use function ob_get_contents;
use function ob_start;
use function trim;

/**
 * The BaseView.
 */
class BaseView implements ViewInterface
{

    /** @var string[] CSS content. */
    private array $_css = [];
    /** @var string[] JS content. */
    private array $_js = [];

    /**
     * File path.
     *
     * @var string
     */
    private string $file;

    /**
     * Params for view files.
     *
     * @var array
     */
    private array $params = [];

    public function __construct(private readonly RendererInterface $renderer)
    {
    }

    public function getRenderer(): RendererInterface
    {
        return $this->renderer;
    }

    public function renderCssFile(string $file): static
    {
        if (!is_file($file)) {
            throw new ViewException('File ' . $file . ' not found');
        }

        $stream = fopen($file, 'r+');
        $streamContent = fread($stream, filesize($file));

        $this->renderCss($streamContent);

        return $this;
    }

    public function renderJsFile(string $file): static
    {
        if (!is_file($file)) {
            throw new ViewException('File ' . $file . ' not found');
        }

        $stream = fopen($file, 'r+');
        $streamContent = fread($stream, filesize($file));

        $this->renderJs($streamContent);

        return $this;
    }

    public function renderCss(string $cssContent): static
    {
        if (empty(trim($cssContent))) {
            throw new ViewException('An empty JavaScript content given.');
        }

        $this->_css[] = $cssContent;

        return $this;
    }

    public function renderJs(string $jsContent): static
    {
        if (empty(trim($jsContent))) {
            throw new ViewException('An empty JavaScript content given.');
        }

        $this->_js[] = $jsContent;

        return $this;
    }

    public function applyResources(ResourceInterface ...$resources): void
    {
        // TODO: Implement applyResources() method.
    }

    /**
     * @param string $file
     * @return $this
     */
    public function withFile(string $file): static
    {
        $this->file = $file;

        return $this;
    }

    public function __toString(): string
    {
        if (!isset($this->file)) {
            throw new ViewException('File is not set.');
        }


        ob_start();
        extract($this->params);

        require_once $this->file;

        $content = ob_get_contents();

        ob_end_clean();

        return sprintf(
            '%s' . PHP_EOL . '<style>%s</style>' . PHP_EOL . '<script>%s</script>',
            $content,
            implode(PHP_EOL, $this->_css),
            implode(PHP_EOL, $this->_js),
        );
    }

    public function withParams(array $params): static
    {
        $this->params = $params;

        return $this;
    }
}