<?php
namespace JWeiland\JwShellExec\Controller;

/*
 * This file is part of the jw_shell_exec project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use JWeiland\JwShellExec\Configuration\ExtConf;
use TYPO3\CMS\Backend\View\BackendTemplateView;
use TYPO3\CMS\Beuser\Domain\Model\BackendUser;
use TYPO3\CMS\Beuser\Domain\Repository\BackendUserRepository;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
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

    public function __construct(ExtConf $extConf = null)
    {
        parent::__construct();

        if ($extConf === null) {
            $extConf = GeneralUtility::makeInstance(ExtConf::class);
        }
        $this->extConf = $extConf;
    }

    /**
     * Action to show a form, where you will see the other logged in users
     */
    public function showAction()
    {
        $this->view->assign('extConf', $this->extConf);
        $this->view->assign('loggedInUsers', $this->getLoggedInUsers());
    }

    /**
     * This action will execute the configured shell script from extensionmanager configuration
     */
    public function execAction()
    {
        $output = '';
        $returnValue = 0;
        CommandUtility::exec($this->extConf->getResolvedShellScript(), $output, $returnValue);

        if ($returnValue === 0) {
            $this->addFlashMessage(
                'Your script was executed without any problems',
                'Executed',
                FlashMessage::OK
            );
        } else {
            $this->addFlashMessage(
                'Your script returns another ReturnValue greater than 0',
                'Oups',
                FlashMessage::ERROR
            );
            $this->addFlashMessage(
                'Maybe helpful: Your script returns following output: ' . $output,
                'Output',
                FlashMessage::INFO
            );
        }
        $this->redirect('show', 'Shell', 'jwShellExec');
    }

    protected function getLoggedInUsers()
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

    /**
     * @return BackendUserAuthentication
     */
    protected function getBackendUserAuthentication(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }
}
