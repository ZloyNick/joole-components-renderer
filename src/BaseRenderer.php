<?php

declare(strict_types=1);

namespace joole\components\renderer;

use joole\framework\content\renderer\view\ViewInterface;
use joole\framework\content\renderer\RendererInterface;
use joole\framework\exception\view\RendererException;
use function fopen;
use function is_dir;
use function is_file;
use function is_null;

/**
 * Base renderer processing view content.
 */
class BaseRenderer implements RendererInterface
{

    /**
     * @throws RendererException|\joole\framework\exception\view\ViewException
     */
    public function renderView(string $file, array $params = [], ?string $namespace = null): ViewInterface
    {
        if (!is_null($namespace)) {
            if (!is_dir($namespace)) {
                throw new RendererException('Invalid namespace given: "' . $namespace . '"');
            }

            $file = $namespace . DIRECTORY_SEPARATOR . $file;
        } else {
            $file = $this->viewsPath . DIRECTORY_SEPARATOR . $file;
        }

        if (!is_file($file)) {
            throw new RendererException('File "' . $file . '" not found');
        }


        return (new BaseView($this))->withFile($file)->withParams($params);
    }

    public function getViewsPath(): string
    {
        return $this->viewsPath;
    }

    public function __construct(private readonly string $viewsPath)
    {
    }
}