<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/jw-shell-exec.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use JWeiland\JwShellExec\Domain\Model\BackendUser;

return [
    BackendUser::class => [
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
