<?php

/**
 * Web app to securely share secrets
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Controller;

use App\Repository\SecretRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ShowSecretController extends AbstractController
{
    public function __construct(private readonly SecretRepositoryInterface $repository)
    {
    }

    #[Route('/{id}', name: 'secrets:show', requirements: ['id' => '[A-Za-z0-9_-]+'], methods: ['GET'])]
    public function __invoke(string $id): Response
    {
        $secret = $this->repository->find($id);

        return $this
            ->render('secrets/show.html.twig', [
                'secret' => $secret,
            ])
            ->setStatusCode($secret ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);
    }
}
