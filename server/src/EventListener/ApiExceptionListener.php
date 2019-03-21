<?php
namespace App\EventListener;

use App\Exception\ApiExceptionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class ApiExceptionListener
{
    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if (!$event->getException() instanceof ApiExceptionInterface) {
            return;
        }

        $response = new JsonResponse($this->buildResponseData($event->getException()));
        $response->setStatusCode($event->getException()->getCode());

        $event->setResponse($response);
    }

    /**
     * @param ApiExceptionInterface $exception
     * @return array
     */
    private function buildResponseData(ApiExceptionInterface $exception)
    {
        if (is_string($exception->getMessage())) {
            $message = $exception->getMessage();
        } elseif (is_array($exception->getMessage())) {
            $message = $exception->getMessage() ? $exception->getMessage()[0] : 'An Error Occured';
        } else {
            $message = 'An Error Occured';
        }

        return [
            'error' => [
                'code' => $exception->getCode(),
                'message' => $message
            ]
        ];
    }
}