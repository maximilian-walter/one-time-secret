<?php

if (!file_exists(__DIR__ . '/src')) {
    exit(0);
}

$headerComment = <<<DOC
Web app to securely share secrets

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.
DOC;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        '@PHP82Migration' => true,
        '@PHP80Migration:risky' => true,
        'concat_space' => ['spacing' => 'one'],
        'header_comment' => [
            'header' => $headerComment,
            'comment_type' => 'PHPDoc',
            'location' => 'after_open',
        ],
        'phpdoc_align' => ['align' => 'left'],
        'phpdoc_summary' => false,
        'single_line_throw' => false,
        'trailing_comma_in_multiline' => [
            'after_heredoc' => true,
            'elements' => ['arrays', 'match', 'parameters'],
        ],
        'use_arrow_functions' => false,
        'nullable_type_declaration_for_default_null_value' => [
            'use_nullable_type_declaration' => true,
        ],
    ])
    ->setRiskyAllowed(true)
    ->setFinder(
        (new PhpCsFixer\Finder())
            ->in(__DIR__ . '/src')
    )
    ->setCacheFile('.php-cs-fixer.cache');
