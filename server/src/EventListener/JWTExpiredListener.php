<?php
namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTExpiredEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationFailureResponse;

class JWTExpiredListener
{
    /**
     * @param JWTExpiredEvent $event
     */
    public function onJWTExpired(JWTExpiredEvent $event)
    {
        /** @var JWTAuthenticationFailureResponse */
        $response = $event->getResponse();
        $response->setStatusCode(419, 'Authentication Timeout');
        $response->setContent(json_encode([
            'error' => [
                'code' => 419,
                'message' => 'Authentication Timeout'
            ]
        ]));
    }
}