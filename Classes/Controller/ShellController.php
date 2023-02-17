<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/jw-shell-exec.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\JwShellExec\Controller;

use JWeiland\JwShellExec\Configuration\ExtConf;
use TYPO3\CMS\Backend\View\BackendTemplateView;
use TYPO3\CMS\Beuser\Domain\Model\BackendUser;
use TYPO3\CMS\Beuser\Domain\Repository\BackendUserRepository;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Utility\CommandUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Controller to show backend module and execute shell script
 */
class ShellController extends ActionController
{
    /**
     * Backend Template Container
     *
     * @var string
     */
    protected $defaultViewObjectName = BackendTemplateView::class;

    /**
     * BackendTemplateContainer
     *
     * @var BackendTemplateView
     */
    protected $view;

    /**
     * @var ExtConf
     */
    protected $extConf;

    public function injectExtConf(ExtConf $extConf): void
    {
        $this->extConf = $extConf;
    }

    /**
     * Action to show a form, where you will see the other logged in users
     */
    public function showAction(): void
    {
        $this->view->assign('extConf', $this->extConf);
        $this->view->assign('loggedInUsers', $this->getLoggedInUsers());
    }

    /**
     * This action will execute the configured shell script from extensionmanager configuration
     */
    public function execAction(): void
    {
        $output = '';
        $returnValue = 0;
        CommandUtility::exec($this->extConf->getResolvedShellScript(), $output, $returnValue);

        if ($returnValue === 0) {
            $this->addFlashMessage(
                'Your script was executed without any problems',
                'Executed'
            );
        } else {
            $this->addFlashMessage(
                'Your script returns another ReturnValue greater than 0',
                'Oups',
                AbstractMessage::ERROR
            );
            $this->addFlashMessage(
                'Maybe helpful: Your script returns following output: ' . $output,
                'Output',
                AbstractMessage::INFO
            );
        }
        $this->redirect('show', 'Shell', 'jwShellExec');
    }

    protected function getLoggedInUsers(): array
    {
        $backendUserRepository = $this->objectManager->get(BackendUserRepository::class);
        /** @var BackendUser[] $loggedInUsers */
        $loggedInUsers = $backendUserRepository->findOnline();
        foreach ($loggedInUsers as $key => $loggedInUser) {
            if ((int)$this->getBackendUserAuthentication()->user['uid'] === $loggedInUser->getUid()) {
                unset($loggedInUsers[$key]);
            }
        }

        $this->view->assign('loggedInUsers', $backendUserRepository->findOnline());

        return $loggedInUsers;
    }

    protected function getBackendUserAuthentication(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }
}
