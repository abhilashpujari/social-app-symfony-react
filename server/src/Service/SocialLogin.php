<?php

namespace App\Service;

use App\Exception\HttpBadRequestException;
use App\Exception\HttpUnauthorizedException;
use Google_Client;
use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;

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
        } elseif ($type === 'facebook') {
            return $this->verifyFacebookUser($token);
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

    /**
     * @param $token
     * @return array
     * @throws HttpUnauthorizedException
     */
    private function verifyFacebookUser($token)
    {
        $fb = new Facebook([
            'app_id' => getenv('FACEBOOK_APP_ID'),
            'app_secret' => getenv('FACEBOOK_APP_SECRET'),
            'default_graph_version' => 'v2.10',
        ]);

        try {
            $response = $fb->get('/me?fields=id,email,first_name,last_name', $token);
        } catch(FacebookResponseException $e) {
            throw new HttpUnauthorizedException('Graph returned an error: ' . $e->getMessage());
        } catch(FacebookSDKException $e) {
            throw new HttpUnauthorizedException('Facebook SDK returned an error: ' . $e->getMessage());
        }

        $user = $response->getGraphUser();

        return (object)[
            'id' => $user['id'],
            'email' => $user['email'],
            'firstName' => $user['first_name'] ? $user['first_name'] : $user['email'],
            'lastName' => $user['last_name'] ? $user['last_name'] : 'NA'
        ];
    }
}