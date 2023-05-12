<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/jw-shell-exec.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

/**
 * Definitions for modules provided by EXT:jw_shell_exec
 */
return [
    'web_jwshellexec' => [
        'parent' => 'web',
        'position' => ['after' => '*'],
        'access' => 'user,admin',
        'icon' => 'EXT:jw_shell_exec/Resources/Public/Icons/Module.svg',
        'labels' => 'LLL:EXT:jw_shell_exec/Resources/Private/Language/locallang_module.xlf',
        'extensionName' => 'JwShellExec',
        'controllerActions' => [
            \JWeiland\JwShellExec\Controller\ShellController::class => [
                'show',
                'exec'
            ],
        ],
    ],
];
