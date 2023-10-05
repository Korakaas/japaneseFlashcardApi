<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionSubscriber implements EventSubscriberInterface
{
    /**
     * Formatte les exceptions du Kernel pour qu'elles puissent être utilisées
     * par le front lors d'appels à l'API.
     *
     * @param ExceptionEvent $event
     * @return void
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if($exception instanceof HttpException) {
            $data = [
                'status' => $exception->getStatusCode(),
                'message' => $exception->getMessage()
            ];

            $event->setResponse(new JsonResponse($data));
        } else {
            $data = [
                'status' => 500,
                'message' => $exception->getMessage()
            ];

            $event->setResponse(new JsonResponse($data));
        }
    }

    /**
     *  Définit les événements auxquels cette classe est abonnée.
     *
     * @return array Un tableau d'événements auxquels cette classe est abonnée.
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
}
