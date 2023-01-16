<?php

/**
 * Web app to securely share secrets
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Secret;

interface SecretRepositoryInterface
{
    public function find(string $id): ?Secret;

    public function exists(string $id): bool;

    public function persist(Secret $secret): void;

    public function remove(Secret $secret): void;
}
