<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/jw-shell-exec.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use JWeiland\JwShellExec\Controller\ShellController;

return [
    'jw_shell_exec' => [
        'parent' => 'web',
        'access' => 'user',
        'workspaces' => 'live',
        'labels' => 'LLL:EXT:jw_shell_exec/Resources/Private/Language/locallang_module.xlf',
        'extensionName' => 'JwShellExec',
        'icon' => 'EXT:jw_shell_exec/Resources/Public/Icons/Module.svg',
        'controllerActions' => [
            ShellController::class => 'show, exec',
        ],
    ],
];
