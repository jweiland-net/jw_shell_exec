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
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Controller to show backend module and execute shell script
 */
class ShellController extends ActionController
{
    /**
     * Action to show a form, where you will see the other logged in users
     */
    public function showAction()
    {
        $this->view->assign('header', 'header');
    }

    /**
     * This action will execute the configured shell script from extensionmanager configuration
     */
    public function execAction()
    {
        $this->addFlashMessage('Executed');
        $this->redirect('show', 'Shell', 'jwShellExec');
    }
}
