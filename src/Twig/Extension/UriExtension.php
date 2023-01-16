<?php

/**
 * Web app to securely share secrets
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Twig\Extension;

use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class UriExtension extends AbstractExtension
{
    public function __construct(private readonly RequestStack $requestStack)
    {
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('base_uri', [$this, 'baseUri']),
        ];
    }

    public function baseUri(): string
    {
        $request = $this->requestStack->getMainRequest();

        return $request->getSchemeAndHttpHost() . $request->getBaseUrl();
    }
}
