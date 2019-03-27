<?php
namespace App\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Exception;

class ApiExceptionListener
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $this->log($event->getException());

        $response = new JsonResponse($this->buildResponseData($event->getException()));
        $errorCode = ($event->getException()->getCode() !== 0) ? $event->getException()->getCode() : 500;
        $response->setStatusCode($errorCode);

        $event->setResponse($response);
    }

    private function log(Exception $e)
    {
        switch ($e->getCode()) {
            case 208:
                // Accepted but not completed
                $this->logger->notice('[208] ' . $e->getMessage());
                break;
            case 400:
                // Bad Request
                $this->logger->notice('[400] ' . $e->getMessage());
                break;
            case 401:
                // Unauthorized
                $this->logger->notice('[401] ' . $e->getMessage());
                break;
            case 403:
                // Forbidden
                $this->logger->notice('[4030] ' . $e->getMessage());
                break;
            case 404:
                // Not Found
                $this->logger->notice('[400] ' . $e->getMessage());
                break;
            case 405:
                // Method not allowed
                $this->logger->notice('[405] ' . $e->getMessage());
                break;
            case 409:
                // Conflict
                $this->logger->notice('[409] ' . $e->getMessage());
                break;
            case 412:
                // Precondition Failed
                $this->logger->notice('[412] ' . $e->getMessage());
                break;
            case 419:
                // Authentication Timeout
                $this->logger->notice('[419] ' . $e->getMessage());
                break;
            case 424:
                // Failed Dependency
                $this->logger->notice('[424] ' . $e->getMessage());
                break;
            case 503:
                // Service Unavailable
                $this->logger->notice('[503' . $e->getMessage());
                break;
            default:
                // Internal Server Error
                $this->logger->error(
                    '[500] ' . $e->getMessage(),
                    [
                        'Stack Trace' => "\n" . $e->getTraceAsString()
                    ]
                );
        }

    }

    /**
     * @param Exception $exception
     * @return array
     */
    private function buildResponseData(Exception $exception)
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
                'code' => $exception->getCode() == 0 ? 500 : $exception->getCode(),
                'message' => $message
            ]
        ];
    }
}