<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

// Register JW Shell Exec module
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
    'JwShellExec',
    'web',
    'jwshell',
    '',
    [
        \JWeiland\JwShellExec\Controller\ShellController::class => 'show, exec',
    ],
    [
        'access' => 'user,group',
        'icon' => 'EXT:jw_shell_exec/Resources/Public/Icons/Module.svg',
        'labels' => 'LLL:EXT:jw_shell_exec/Resources/Private/Language/locallang_module.xlf',
    ]
);
