<?php

declare(strict_types=1);

return [
    \JWeiland\JwShellExec\Domain\Model\BackendUser::class => [
        'tableName' => 'be_users',
        'properties' => [
            'userName' => [
                'fieldName' => 'username',
            ],
            'realName' => [
                'fieldName' => 'realName',
            ],
        ],
    ],
];
