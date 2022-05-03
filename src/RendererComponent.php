<?php

declare(strict_types=1);

namespace joole\components\renderer;

use joole\framework\Application;
use joole\framework\component\BaseComponent;
use joole\framework\exception\component\ComponentException;

/**
 * The renderer component for joole framework.
 */
class RendererComponent extends BaseComponent
{

    /** @var string Default view path. */
    private string $viewsPath;

    /**
     * @throws ComponentException
     */
    public function init(array $options): void
    {
        if(!isset($options['views'])){
            throw new ComponentException('"views" configurations key not found!');
        }

        if(!is_dir($options['views'])){
            throw new ComponentException('Views path "'.$options['views'].'" not found!');
        }

        $this->viewsPath = $options['views'];
    }

    public function run(Application $app): void
    {
        $renderer = new BaseRenderer($this->viewsPath);

        $app->setRenderer($renderer);
    }
}