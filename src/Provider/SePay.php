<?php

declare(strict_types=1);

namespace Datlechin\OAuth2\Client\Provider;

use Datlechin\OAuth2\Client\Provider\Exception\SePayIdentityProviderException;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

class SePay extends AbstractProvider
{
    use BearerAuthorizationTrait;

    public const BASE_URL = 'https://my.sepay.vn';

    public function getBaseAuthorizationUrl(): string
    {
        return self::BASE_URL . '/oauth/authorize';
    }

    public function getBaseAccessTokenUrl(array $params): string
    {
        return self::BASE_URL . '/oauth/token';
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token): string
    {
        return self::BASE_URL . '/api/v1/me';
    }

    protected function getDefaultScopes(): array
    {
        return ['profile'];
    }

    protected function checkResponse(ResponseInterface $response, $data): void
    {
        if ($response->getStatusCode() >= 400) {
            throw SePayIdentityProviderException::clientException($response, $data);
        } elseif (isset($data['error'])) {
            throw SePayIdentityProviderException::oauthException($response, $data);
        }
    }

    protected function createResourceOwner(array $response, AccessToken $token): SePayResourceOwner
    {
        return new SePayResourceOwner($response);
    }
}
