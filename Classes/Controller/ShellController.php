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
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Core\Utility\CommandUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Controller to show backend module and execute shell script
 */
class ShellController extends ActionController
{
    use AddShortcutButtonTrait;

    public function __construct(
        private readonly ModuleTemplateFactory $moduleTemplateFactory,
        private readonly BackendUserRepository $backendUserRepository,
        private readonly ExtConf $extConf,
    ) {}

    /**
     * Action to show a form, where you will see the other logged-in users
     */
    public function showAction(): ResponseInterface
    {
        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);

        $moduleTemplate->assign('extConf', $this->extConf);
        if (!$this->extConf->isAllowParallelExecution()) {
            $moduleTemplate->assign('loggedInUsers', $this->backendUserRepository->findSiblingsOnline());
        }

        $this->addShortcutToButtonBar(
            $moduleTemplate->getDocHeaderComponent()->getButtonBar(),
            'web_JwShellExecJwShellExec',
            'JW Shell Exec'
        );

        return $moduleTemplate->renderResponse('Show');
    }

    /**
     * This action will execute the configured shell script from Extension Settings
     */
    public function execAction(): ResponseInterface
    {
        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);

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
                ContextualFeedbackSeverity::ERROR,
            );
        }

        $moduleTemplate->assignMultiple([
            'extConf' => $this->extConf,
            'output' => array_map('trim', $output),
        ]);

        if (!$this->extConf->isAllowParallelExecution()) {
            $moduleTemplate->assign('loggedInUsers', $this->backendUserRepository->findSiblingsOnline());
        }

        $this->addShortcutToButtonBar(
            $moduleTemplate->getDocHeaderComponent()->getButtonBar(),
            'web_JwShellExecJwShellExec',
            'JW Shell Exec'
        );

        return $moduleTemplate->renderResponse('Exec');
    }
}
