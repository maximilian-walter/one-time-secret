<?php

/**
 * Web app to securely share secrets
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Controller\Api\Secrets;

use App\Repository\SecretRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

final class ShowController extends AbstractController
{
    public function __construct(private readonly SecretRepositoryInterface $repository)
    {
    }

    #[Route('/api/secrets/{id}', name: 'api:secrets:show', requirements: ['id' => '[A-Za-z0-9_-]+'], methods: ['GET'])]
    public function __invoke(Request $request, string $id): Response
    {
        $csrfToken = $request->headers->get('X-Csrf-Token') ?? null;

        if (!$this->isCsrfTokenValid('api', $csrfToken)) {
            throw new BadRequestHttpException('Invalid CSRF token');
        }

        $entity = $this->repository->find($id);

        return $this->json($entity);
    }
}
