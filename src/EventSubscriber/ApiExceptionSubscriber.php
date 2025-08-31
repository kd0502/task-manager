<?php
namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

final class ApiExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::EXCEPTION => 'onException'];
    }

    public function onException(ExceptionEvent $event): void
    {
        $req = $event->getRequest();
        if (!str_starts_with($req->getPathInfo(), '/api')) {
            return;
        }

        $e = $event->getThrowable();
        $status = $e instanceof HttpExceptionInterface ? $e->getStatusCode() : 500;

        $event->setResponse(new JsonResponse([
            'error' => $e->getMessage(),
        ], $status));
    }
}
