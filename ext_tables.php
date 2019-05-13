<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

// Register JW Shell Exec module
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
    'JWeiland.jwShellExec',
    'web',
    'jwshell',
    '',
    [
        'Shell' => 'show, exec',
    ],
    [
        'access' => 'user,group',
        'icon' => 'EXT:jw_shell_exec/Resources/Public/Icons/module.gif',
        'labels' => 'LLL:EXT:jw_shell_exec/Resources/Private/Language/locallang_module.xlf',
    ]
);
