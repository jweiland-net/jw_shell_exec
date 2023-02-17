<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/jw-shell-exec.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\JwShellExec\Configuration;

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
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
     * This method reads the global configuration and calls the setter methods.
     */
    public function __construct()
    {
        // get global configuration
        $extConf = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('jw_shell_exec');
        if (is_array($extConf)) {
            // call setter method foreach configuration entry
            foreach ($extConf as $key => $value) {
                $methodName = 'set' . ucfirst($key);
                if (method_exists($this, $methodName)) {
                    $this->$methodName((string)$value);
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
        if ($this->getShellScriptBeginsWithExt()) {
            return GeneralUtility::getFileAbsFileName($this->shellScript);
        }

        return $this->getShellScript();
    }

    public function getShellScriptBeginsWithExt(): bool
    {
        return strpos($this->shellScript, 'EXT:') === 0;
    }

    public function getShellScriptExists(): bool
    {
        return @is_file($this->shellScript);
    }

    public function getShellScriptExecutable(): bool
    {
        return @is_executable($this->shellScript);
    }

    public function setShellScript(string $shellScript): void
    {
        $this->shellScript = $shellScript;
    }
}
