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
use League\Flysystem\FilesystemOperator;
use Webmozart\Assert\Assert;

final class FlysystemSecretRepository implements SecretRepositoryInterface
{
    public function __construct(private readonly FilesystemOperator $filesystem)
    {
    }

    public function find(string $id): ?Secret
    {
        $filename = $this->generateFilename($id);
        if (!$this->filesystem->has($filename)) {
            return null;
        }

        $secret = $this->filesystem->read($filename);

        return new Secret($id, $secret);
    }

    public function exists(string $id): bool
    {
        return null !== $this->find($id);
    }

    public function persist(Secret $secret): void
    {
        $filename = $this->generateFilename($secret->id);

        $this->filesystem->write($filename, $secret->secret);
    }

    public function remove(Secret $secret): void
    {
        $filename = $this->generateFilename($secret->id);

        $this->filesystem->delete($filename);
    }

    private function generateFilename(string $id): string
    {
        Assert::regex($id, Secret::VALID_ID_PATTERN);

        return $id . '.bin';
    }
}
