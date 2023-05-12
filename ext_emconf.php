<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Execute shell script',
    'description' => 'With this extension you can execute exactly one defined Shell command as long as only one editor is logged into backend',
    'category' => 'plugin',
    'state' => 'stable',
    'author' => 'Stefan Froemken',
    'author_email' => 'projects@jweiland.net',
    'author_company' => 'jweiland.net',
    'version' => '3.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.23-12.4.99',
        ],
        'conflicts' => [
        ],
        'suggests' => [
        ],
    ],
];
