<?php

/**
 * Web app to securely share secrets
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Flysystem;

use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemOperator;
use League\Flysystem\Local\LocalFilesystemAdapter;

final class FilesystemFactory
{
    public static function createForLocal(string $root): FilesystemOperator
    {
        return new Filesystem(new LocalFilesystemAdapter($root));
    }
}
