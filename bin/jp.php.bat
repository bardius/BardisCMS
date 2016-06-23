@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../vendor/mtdowling/jmespath.php/bin/jp.php
php "%BIN_TARGET%" %*
