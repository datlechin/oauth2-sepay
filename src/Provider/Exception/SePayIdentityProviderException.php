<?php

declare(strict_types=1);

namespace Datlechin\OAuth2\Client\Provider\Exception;

use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Psr\Http\Message\ResponseInterface;

class SePayIdentityProviderException extends IdentityProviderException
{
    public static function clientException(ResponseInterface $response, $data): static
    {
        return static::fromResponse(
            $response,
            $data['message'] ?? $response->getReasonPhrase()
        );
    }

    public static function oauthException(ResponseInterface $response, $data): static
    {
        return static::fromResponse(
            $response,
            $data['error'] ?? $response->getReasonPhrase()
        );
    }

    protected static function fromResponse(ResponseInterface $response, $message = null): static
    {
        return new static($message, $response->getStatusCode(), (string) $response->getBody());
    }
}
