<?php

/**
 * Web app to securely share secrets
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Controller\Api\Secrets;

use App\Entity\Secret;
use App\Repository\SecretRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Webmozart\Assert\Assert;

final class CreateController extends AbstractController
{
    public function __construct(private readonly SecretRepositoryInterface $repository)
    {
    }

    #[Route('/api/secrets', name: 'api:secrets:create', methods: ['POST'])]
    public function __invoke(Request $request): Response
    {
        $requestBody = $request->toArray();

        $id = $requestBody['id'] ?? null;
        $secret = $requestBody['secret'] ?? null;
        $csrfToken = $request->headers->get('X-Csrf-Token') ?? null;

        if (!$this->isCsrfTokenValid('api', $csrfToken)) {
            throw new BadRequestHttpException('Invalid CSRF token');
        }

        try {
            Assert::regex($id, Secret::VALID_ID_PATTERN);
            Assert::regex($secret, Secret::VALID_SECRET_PATTERN);
        } catch (\InvalidArgumentException) {
            throw new BadRequestHttpException('Invalid request body');
        }

        if ($this->repository->exists($id)) {
            throw new ConflictHttpException('A secret with the ID "' . $id . '" already exists');
        }

        $entity = new Secret($id, $secret);

        $this->repository->persist($entity);

        return $this->json($entity, Response::HTTP_CREATED, [
            'Location' => $this->generateUrl('secrets:show', [
                'id' => $entity->id,
            ], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
    }
}
