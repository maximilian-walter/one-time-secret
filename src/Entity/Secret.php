<?php

/**
 * Web app to securely share secrets
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Entity;

final class Secret
{
    public const VALID_ID_PATTERN = '/^[A-Za-z0-9_-]{1,64}$/';
    public const VALID_SECRET_PATTERN = '/^[A-Za-z0-9+\/=]{1,1500}$/';

    public function __construct(
        public readonly string $id,
        public readonly string $secret
    ) {
    }
}
