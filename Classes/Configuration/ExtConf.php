<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/jw-shell-exec.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\JwShellExec\Configuration;

use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\CommandUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * This class streamlines all settings from extension manager
 */
class ExtConf implements SingletonInterface
{
    /**
     * Shell script path
     */
    protected string $shellScript = '';

    /**
     * This method reads the global configuration and calls the setter methods.
     */
    public function __construct()
    {
        try {
            $extConf = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('jw_shell_exec');
            if (is_array($extConf)) {
                // Call setter method foreach configuration entry
                foreach ($extConf as $key => $value) {
                    $methodName = 'set' . ucfirst($key);
                    if (method_exists($this, $methodName)) {
                        $this->$methodName((string)$value);
                    }
                }
            }
        } catch (ExtensionConfigurationExtensionNotConfiguredException | ExtensionConfigurationPathDoesNotExistException $e) {
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
        return CommandUtility::checkCommand($this->getExecutable());
    }

    public function getExecutable(): string
    {
        $parts = GeneralUtility::trimExplode(' ', $this->shellScript, true);
        if ($parts === []) {
            return '';
        }

        return $parts[0];
    }

    public function setShellScript(string $shellScript): void
    {
        $this->shellScript = $shellScript;
    }

    public function getPreparedShellCommand(): string
    {
        $parts = GeneralUtility::trimExplode(' ', $this->shellScript, true);
        if (count($parts) < 2) {
            return $this->getExecutable();
        }

        $executable = array_shift($parts);
        $parts = array_map(function ($argument) {
            return CommandUtility::escapeShellArgument($argument);
        }, $parts);

        return $executable . ' ' . implode(' ', $parts);
    }
}
