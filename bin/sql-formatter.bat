@ECHO OFF
SET BIN_TARGET=%~dp0/../vendor/jdorn/sql-formatter/bin/sql-formatter
php "%BIN_TARGET%" %*
