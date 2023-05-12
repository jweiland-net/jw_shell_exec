<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/jw-shell-exec.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\JwShellExec\Controller\Traits;

use TYPO3\CMS\Backend\Template\Components\ButtonBar;

trait AddShortcutButtonTrait
{
    protected function addShortcutToButtonBar(
        ButtonBar $buttonBar,
        string $routeIdentifier,
        string $title,
        array $arguments = []
    ): void {
        $shortcutButton = $buttonBar->makeShortcutButton()
            ->setRouteIdentifier($routeIdentifier)
            ->setDisplayName($title)
            ->setArguments($arguments);

        $buttonBar->addButton($shortcutButton);
    }
}
