# TYPO3 Extension `jw_shell_exec`

![Build Status](https://github.com/jweiland-net/jw_shell_exec/workflows/CI/badge.svg)

With this extension you can execute exactly one defined Shell command as long as only
one editor is logged into backend

## 1 Features

* Configure an executable script in extension settings and execute it from TYPO3 backend

## 2 Usage

### 2.1 Installation

#### Installation using Composer

The recommended way to install the extension is using Composer.

Run the following command within your Composer based TYPO3 project:

```
composer require jweiland/jw-shell-exec
```

#### Installation as extension from TYPO3 Extension Repository (TER)

Download and install `jw_shell_exec` with the extension manager module.

### 2.2 Minimal setup

1) Configure an absolute path to an executable script in extension settings
2) Check, that just you are logged in into backend
3) Visit JW Shell Exec BE module and start the script
