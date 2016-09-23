<?php

$fixers = array(
    '-psr0',
    'double_arrow_multiline_whitespaces',
    'duplicate_semicolon',
    'empty_return',
    'extra_empty_lines',
    'include',
    'namespace_no_leading_whitespace',
    'new_with_braces',
    'object_operator',
    'operators_spaces',
    'remove_leading_slash_use',
    'remove_lines_between_uses',
    'return',
    'single_array_no_trailing_comma',
    'spaces_before_semicolon',
    'spaces_cast',
    'ternary_spaces',
    'unused_use',
    'whitespacy_lines',
    'align_double_arrow',
    'align_equals',
    'concat_with_spaces',
    'multiline_spaces_before_semicolon',
    'ordered_use',
    'short_array_syntax',
    'indentation',
);

$finder = Symfony\CS\Finder\DefaultFinder::create();
$finder->in(__DIR__ . '/src');
$finder->in(__DIR__ . '/tests');

return Symfony\CS\Config\Config::create()
    ->level(Symfony\CS\FixerInterface::PSR2_LEVEL)
    ->fixers($fixers)
    ->finder($finder);
