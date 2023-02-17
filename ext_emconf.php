<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Execute shell script',
    'description' => 'With this extension you can execute exactly one defined Shell command as long as only one editor is logged into backend',
    'category' => 'plugin',
    'author' => 'Stefan Froemken',
    'author_email' => 'projects@jweiland.net',
    'author_company' => 'jweiland.net',
    'clearCacheOnLoad' => 0,
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.32-11.5.99',
        ],
        'conflicts' => [
        ],
        'suggests' => [
        ],
    ],
];
