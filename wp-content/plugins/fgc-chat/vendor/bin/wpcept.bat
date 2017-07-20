@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../lucatume/wp-browser/wpcept
php "%BIN_TARGET%" %*
