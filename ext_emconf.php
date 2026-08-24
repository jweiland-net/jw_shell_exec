<?php

/*
 * This file is part of the package jweiland/jw-shell-exec.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

$EM_CONF[$_EXTKEY] = [
    'title' => 'Execute shell script',
    'description' => 'With this extension you can execute exactly one defined Shell command as long as only one editor is logged into backend',
    'category' => 'plugin',
    'state' => 'stable',
    'author' => 'Stefan Froemken',
    'author_email' => 'projects@jweiland.net',
    'author_company' => 'jweiland.net',
    'version' => '4.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '13.4.34-13.4.99',
        ],
        'conflicts' => [
        ],
        'suggests' => [
        ],
    ],
];
