<?php

$header = <<<'EOF'
This file is part of BardisCMS.

(c) George Bardis <george@bardis.info>

This source file is subject to the MIT license that is bundled
with this source code in the file LICENSE.
EOF;

Symfony\CS\Fixer\Contrib\HeaderCommentFixer::setHeader($header);

return Symfony\CS\Config::create()
    // use default SYMFONY_LEVEL and extra fixers:
    ->level(Symfony\CS\FixerInterface::SYMFONY_LEVEL)
    ->fixers(array(
        'combine_consecutive_unsets',
        'header_comment',
        'long_array_syntax',
        'no_useless_else',
        'no_useless_return',
        'ordered_use',
        'php_unit_construct',
        'php_unit_strict',
        'strict',
        'strict_param',
    ))
    ->finder(
        Symfony\CS\Finder::create()
            ->exclude(
                array(
                    'app',
                    'node_modules',
                    'ui-src',
                    'vendor',
                    'web',
                    'cache',
                    'logs',
                    'Resources',
                    'Tests',
                )
            )
            ->in(__DIR__)
    )
;
