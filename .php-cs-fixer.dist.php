<?php

/**
 * The web-app for dp DIGITAL PUBLISHERS
 *
 * @copyright Seitenblick digitale Medien GmbH
 */

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
        '@PHP81Migration' => true,
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
        'use_arrow_functions' => false,
    ])
    ->setRiskyAllowed(true)
    ->setFinder(
        (new PhpCsFixer\Finder())
            ->in(__DIR__ . '/src')
    )
    ->setCacheFile('.php-cs-fixer.cache');
