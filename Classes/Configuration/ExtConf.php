<?php

namespace JWeiland\JwShellExec\Configuration;

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
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * This class streamlines all settings from extension manager
 */
class ExtConf implements SingletonInterface
{
    /**
     * shell script path
     *
     * @var string
     */
    protected $shellScript = '';

    /**
     * constructor of this class
     * This method reads the global configuration and calls the setter methods.
     */
    public function __construct()
    {
        // On a fresh installation this value can be null.
        if (isset($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['jw_shell_exec'])) {
            // get global configuration
            $extConf = unserialize(
                $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['jw_shell_exec'],
                ['allowed_classes' => false]
            );
            if (is_array($extConf) && count($extConf)) {
                // call setter method foreach configuration entry
                foreach ($extConf as $key => $value) {
                    $methodName = 'set' . ucfirst($key);
                    if (method_exists($this, $methodName)) {
                        $this->$methodName($value);
                    }
                }
            }
        }
    }

    public function getShellScript(): string
    {
        return $this->shellScript;
    }

    public function getResolvedShellScript(): string
    {
        return GeneralUtility::getFileAbsFileName($this->shellScript);
    }

    public function getShellScriptBeginsWithExt()
    {
        return strpos($this->shellScript, 'EXT:') === 0;
    }

    public function getShellScriptExists()
    {
        return @is_file($this->shellScript);
    }

    public function getShellScriptExecutable()
    {
        return @is_executable($this->shellScript);
    }

    public function setShellScript(string $shellScript)
    {
        $this->shellScript = $shellScript;
    }

}
