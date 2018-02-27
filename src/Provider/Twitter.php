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

    const API_VERSION = 1.1;

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
        return null;
    }

    public function getStandardSearch(AccessToken $token, $query, $params = [])
    {
        $response = $this->fetchStandardSearch($token, $query, $params);
        return $response;
    }

    public function getStandardSearchUrl($query, $params = [])
    {
        $params = array_merge(['q' => $query], $params);

        return sprintf('https://api.twitter.com/%s/search/tweets.json?', static::API_VERSION) . http_build_query($params);
    }

    protected function fetchStandardSearch(AccessToken $token, $query, $params = [])
    {
        $url = $this->getStandardSearchUrl($query, $params);

        $request = $this->getAuthenticatedRequest(self::METHOD_GET, $url, $token);

        $response = $this->getParsedResponse($request);

        if (false === is_array($response)) {
            throw new UnexpectedValueException(
                'Invalid response received from Authorization Server. Expected JSON.'
            );
        }

        return $response;
    }
}