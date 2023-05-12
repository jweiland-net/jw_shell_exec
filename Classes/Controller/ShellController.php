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
use JWeiland\JwShellExec\Controller\Traits\AddShortcutButtonTrait;
use JWeiland\JwShellExec\Domain\Repository\BackendUserRepository;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Core\Utility\CommandUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Controller to show backend module and execute shell script
 */
class ShellController extends ActionController
{
    use AddShortcutButtonTrait;

    protected ModuleTemplateFactory $moduleTemplateFactory;

    protected ExtConf $extConf;

    protected BackendUserRepository $backendUserRepository;

    public function injectModuleTemplateFactory(ModuleTemplateFactory $moduleTemplateFactory): void
    {
        $this->moduleTemplateFactory = $moduleTemplateFactory;
    }

    public function injectExtConf(ExtConf $extConf): void
    {
        $this->extConf = $extConf;
    }

    public function injectBackendUserRepository(BackendUserRepository $backendUserRepository): void
    {
        $this->backendUserRepository = $backendUserRepository;
    }

    /**
     * Action to show a form, where you will see the other logged-in users
     */
    public function showAction(): ResponseInterface
    {
        $this->view->assign('extConf', $this->extConf);
        if (!$this->extConf->isAllowParallelExecution()) {
            $this->view->assign('loggedInUsers', $this->backendUserRepository->findSiblingsOnline());
        }

        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $this->addShortcutToButtonBar(
            $moduleTemplate->getDocHeaderComponent()->getButtonBar(),
            'web_JwShellExecJwShellExec',
            'JW Shell Exec'
        );

        $moduleTemplate->setContent($this->view->render());

        return $this->htmlResponse($moduleTemplate->renderContent());
    }

    /**
     * This action will execute the configured shell script from Extension Settings
     */
    public function execAction(): ResponseInterface
    {
        $output = [];
        $returnValue = 0;
        CommandUtility::exec($this->extConf->getPreparedShellCommand(), $output, $returnValue);

        if ($returnValue === 0) {
            $this->addFlashMessage(
                'Your script was executed without any problems',
                'Executed'
            );
        } else {
            $this->addFlashMessage(
                'Your script returns a return value greater than 0',
                'Oups',
                AbstractMessage::ERROR
            );
        }

        $this->view->assignMultiple([
            'extConf' => $this->extConf,
            'output' => array_map('trim', $output),
        ]);

        if (!$this->extConf->isAllowParallelExecution()) {
            $this->view->assign('loggedInUsers', $this->backendUserRepository->findSiblingsOnline());
        }

        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $this->addShortcutToButtonBar(
            $moduleTemplate->getDocHeaderComponent()->getButtonBar(),
            'web_JwShellExecJwShellExec',
            'JW Shell Exec'
        );

        $moduleTemplate->setContent($this->view->render());

        return $this->htmlResponse($moduleTemplate->renderContent());
    }
}
