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
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

final class ShowController extends AbstractController
{
    public function __construct(
        private readonly SecretRepositoryInterface $repository,
        private readonly LoggerInterface $accessLogger
    ) {
    }

    #[Route('/api/secrets/{id}', name: 'api:secrets:show', requirements: ['id' => '[A-Za-z0-9_-]+'], methods: ['GET'])]
    public function __invoke(Request $request, string $id): Response
    {
        $csrfToken = $request->headers->get('X-Csrf-Token') ?? null;

        if (!$this->isCsrfTokenValid('api', $csrfToken)) {
            throw new BadRequestHttpException('Invalid CSRF token');
        }

        $entity = $this->repository->find($id);
        if (!$entity) {
            throw new NotFoundHttpException('The secret was not found');
        }

        $this->repository->remove($entity);

        $this->accessLogger->info(sprintf('The secret %s was accessed by %s', $id, $request->getClientIp()), [
            'secret_id' => $id,
            'client_ip' => $request->getClientIp(),
            'user_agent' => $request->server->get('HTTP_USER_AGENT'),
        ]);

        return $this->json($entity);
    }
}
