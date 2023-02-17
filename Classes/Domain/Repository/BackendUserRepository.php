<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/jw-shell-exec.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\JwShellExec\Domain\Repository;

use JWeiland\JwShellExec\Domain\Model\BackendUser;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Session\Backend\SessionBackendInterface;
use TYPO3\CMS\Core\Session\SessionManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Repository for backend users
 */
class BackendUserRepository extends Repository
{
    public function initializeObject(): void
    {
        /** @var Typo3QuerySettings $querySettings */
        $querySettings = $this->objectManager->get(Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($querySettings);
    }

    /**
     * Find Backend Users currently online
     *
     * @return BackendUser[]
     */
    public function findOnline(): array
    {
        $uids = [];
        foreach ($this->getSessionBackend()->getAll() as $sessionRecord) {
            if (isset($sessionRecord['ses_userid']) && !in_array($sessionRecord['ses_userid'], $uids, true)) {
                $uids[] = $sessionRecord['ses_userid'];
            }
        }

        $query = $this->createQuery();
        $query->matching($query->in('uid', $uids));

        return $query->execute()->toArray();
    }

    protected function getSessionBackend(): SessionBackendInterface
    {
        $loginType = $this->getBackendUserAuthentication()->getLoginType();

        return GeneralUtility::makeInstance(SessionManager::class)->getSessionBackend($loginType);
    }

    protected function getBackendUserAuthentication(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }
}
