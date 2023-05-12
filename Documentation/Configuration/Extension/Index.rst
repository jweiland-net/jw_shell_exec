..  include:: /Includes.rst.txt


..  _extensionSettings:

==================
Extension Settings
==================

Some general settings for `jw_shell_exec` can be configured
in *Admin Tools -> Settings*.


Tab: Basic
==========

Shell Script
------------

Default: `pwd`

Define a path to an executable shell script.

..  note::

    If you want to execute OS commands like `pwd` with `jw_shell_exec` there
    is no need to add full absolute path. `jw_shell_exec` will automatically
    search in various PATHs like `usr/bin/`, ...

..  warning::

    `$GLOBALS['TYPO3_CONF_VARS']['BE']['disable_exec_function']` has to be
    disabled. Else, the script will never be executed.

..  warning::

    The `EXT:` prefix is not allowed anymore! Please register your shell
    script with composer. That way it is automatically available from
    `vendor/bin/` path. If you still need your own path
    please add full absolute directory path to your shell script
    in `$GLOBALS['TYPO3_CONF_VARS']['SYS']['binPath']` and enter just the
    filename of your script here.

..  warning::

    Don't forget to set execution rights to your own shell script.


Allow parallel execution
------------------------

Default: false

To prevent side-effects when BE users have started the script
at the same, the parallel execution is disabled by default. If you're
sure that no side-effects will occure you can activate this option.
