<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/jw-shell-exec.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\JwShellExec\Domain\Repository;

use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Session\Backend\SessionBackendInterface;
use TYPO3\CMS\Core\Session\SessionManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Repository for backend users
 *
 * I have adopted the needed methods from TYPO3 core's BackendUserRepository
 */
class BackendUserRepository extends Repository
{
    /**
     * Find Backend Users currently online, except the current logged-in user
     */
    public function findSiblingsOnline(): QueryResultInterface
    {
        $uids = [];
        foreach ($this->getSessionBackend()->getAll() as $sessionRecord) {
            if (isset($sessionRecord['ses_userid']) && !in_array($sessionRecord['ses_userid'], $uids, true)) {
                $uids[] = $sessionRecord['ses_userid'];
            }
        }

        $query = $this->createQuery();
        $query->matching($query->logicalAnd(
            $query->in('uid', $uids),
            $query->logicalNot(
                $query->equals('uid', $this->getBackendUserAuthentication()->user['uid'])
            )
        ));

        return $query->execute();
    }

    /**
     * Overwrite createQuery to don't respect enable fields
     */
    public function createQuery(): QueryInterface
    {
        $query = parent::createQuery();
        $query->getQuerySettings()->setIgnoreEnableFields(true);

        return $query;
    }

    protected function getSessionBackend(): SessionBackendInterface
    {
        return GeneralUtility::makeInstance(SessionManager::class)->getSessionBackend('BE');
    }

    private function getBackendUserAuthentication(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }
}
