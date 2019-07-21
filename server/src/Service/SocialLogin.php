<?php

namespace App\Service;

use App\Exception\HttpBadRequestException;
use App\Exception\HttpUnauthorizedException;
use Google_Client;

/**
 * Class SocialLogin
 * @package App\Service
 */
class SocialLogin
{
    /**
     * @param $type
     * @param $token
     * @return array
     * @throws HttpBadRequestException
     */
    public function verifySocialUser($type, $token)
    {
        if ($type === 'google') {
            return $this->verifyGoogleUser($token);
        } else {
            throw new HttpBadRequestException('Invalid Social Provider');
        }
    }

    /**
     * @param $token
     * @return array
     * @throws HttpUnauthorizedException
     */
    private function verifyGoogleUser($token)
    {
        $client = new Google_Client(['client_id' => getenv('GOOGLE_CLIENT_ID')]);
        $payload = $client->verifyIdToken($token);
        if ($payload) {
            return (object)[
                'id' => $payload['sub'],
                'email' => $payload['email'],
                'firstName' => $payload['given_name'] ? $payload['given_name'] : $payload['email'],
                'lastName' => $payload['family_name'] ? $payload['family_name'] : 'NA'
            ];
        } else {
            throw new HttpUnauthorizedException('Unauthorized user!!!');
        }
    }
}