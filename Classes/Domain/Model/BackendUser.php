<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/jw-shell-exec.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\JwShellExec\Domain\Model;

use TYPO3\CMS\Extbase\Annotation as Extbase;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Model for backend user
 *
 * I have adopted the needed methods from TYPO3 core's BackendUser
 */
class BackendUser extends AbstractEntity
{
    /**
     * @Extbase\Validate("NotEmpty")
     */
    protected string $userName = '';

    protected string $realName = '';

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function setUserName(string $userName): void
    {
        $this->userName = $userName;
    }

    public function getRealName(): string
    {
        return $this->realName;
    }

    public function setRealName(string $name): void
    {
        $this->realName = $name;
    }
}
