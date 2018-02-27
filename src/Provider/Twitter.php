<?php

namespace League\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;

use Psr\Http\Message\ResponseInterface;

class Twitter extends AbstractProvider
{
    use BearerAuthorizationTrait;

    public function getBaseAuthorizationUrl()
    {
        return null;
    }

    public function getBaseAccessTokenUrl(array $params)
    {
        return 'https://api.twitter.com/oauth2/token';
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return '';
    }

    protected function getDefaultScopes()
    {
        return [];
    }

    protected function checkResponse(ResponseInterface $response, $data)
    {
        // Standard error response format
        if (!empty($data['errors'])) {
            $code  = $data['errors'][0]['code'];
            $error = $data['errors'][0]['message'];

            throw new IdentityProviderException($error, $code, $response);
        }
    }

    protected function createResourceOwner(array $response, AccessToken $token)
    {
        null;
    }
}