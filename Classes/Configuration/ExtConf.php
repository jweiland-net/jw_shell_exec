<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/jw-shell-exec.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\JwShellExec\Configuration;

use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\CommandUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * This class streamlines all settings from extension manager
 */
#[Autoconfigure(constructor: 'create')]
final readonly class ExtConf
{
    private const EXT_KEY = 'maps2';

    private const DEFAULT_SETTINGS = [
        'shellScript' => '',
        'allowParallelExecution' => false,
    ];

    public function __construct(
        private string $shellScript = self::DEFAULT_SETTINGS['shellScript'],
        private bool $allowParallelExecution = self::DEFAULT_SETTINGS['allowParallelExecution'],
    ) {}

    public static function create(ExtensionConfiguration $extensionConfiguration): self
    {
        $extensionSettings = self::DEFAULT_SETTINGS;

        // Overwrite default extension settings with values from EXT_CONF
        try {
            $extensionSettings = array_merge(
                $extensionSettings,
                $extensionConfiguration->get(self::EXT_KEY),
            );
        } catch (ExtensionConfigurationExtensionNotConfiguredException|ExtensionConfigurationPathDoesNotExistException) {
        }

        return new self(
            shellScript: (string)$extensionSettings['shellScript'],
            allowParallelExecution: (bool)$extensionSettings['allowParallelExecution'],
        );
    }

    public function getShellScript(): string
    {
        return $this->shellScript;
    }

    public function isAllowParallelExecution(): bool
    {
        return $this->allowParallelExecution;
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

    public function getPreparedShellCommand(): string
    {
        $parts = GeneralUtility::trimExplode(' ', $this->shellScript, true);
        if (count($parts) < 2) {
            return CommandUtility::getCommand($this->getExecutable());
        }

        $executable = array_shift($parts);
        $parts = array_map(function ($argument) {
            return CommandUtility::escapeShellArgument($argument);
        }, $parts);

        return CommandUtility::getCommand($executable) . ' ' . implode(' ', $parts);
    }
}
