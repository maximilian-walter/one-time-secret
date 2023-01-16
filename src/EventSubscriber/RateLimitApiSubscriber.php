<?php

/**
 * Web app to securely share secrets
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\RateLimiter\RateLimiterFactory;

class RateLimitApiSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly RateLimiterFactory $apiLimiter)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onRequest',
        ];
    }

    public function onRequest(RequestEvent $event): void
    {
        if ($this->shouldRequestBeRateLimited($event)) {
            $limiter = $this->apiLimiter->create($event->getRequest()->getClientIp());

            if (false === $limiter->consume()->isAccepted()) {
                throw new TooManyRequestsHttpException();
            }
        }
    }

    private function shouldRequestBeRateLimited(RequestEvent $event): bool
    {
        if (!$event->isMainRequest()) {
            return false;
        }

        return str_starts_with($event->getRequest()->attributes->get('_route', ''), 'api:');
    }
}
