@echo off
REM
REM phire-console main Windows CLI batch script
REM

SETLOCAL ENABLEDELAYEDEXPANSION

SET SCRIPT_DIR=%~dp0

php %SCRIPT_DIR%phire.php %*