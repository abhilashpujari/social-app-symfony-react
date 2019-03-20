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
        $messages = json_decode($exception->getMessage());
        if (!is_array($messages)) {
            $messages = $exception->getMessage() ? [$exception->getMessage()] : [];
        }

        return [
            'error' => [
                'code' => $exception->getCode(),
                'messages' => $messages
            ]];
    }
}