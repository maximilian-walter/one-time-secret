<?php

/**
 * Web app to securely share secrets
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CreateSecretController extends AbstractController
{
    #[Route('/create', name: 'secrets:create', methods: ['GET'])]
    public function __invoke(): Response
    {
        return $this->render('secrets/create.html.twig');
    }
}
