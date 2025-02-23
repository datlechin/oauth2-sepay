<?php

declare(strict_types=1);

namespace Datlechin\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Tool\ArrayAccessorTrait;

class SePayResourceOwner implements ResourceOwnerInterface
{
    use ArrayAccessorTrait;

    protected array $data;

    public function __construct(array $response)
    {
        $this->data = $response['data'];
    }

    public function getId(): int
    {
        return $this->getValueByKey($this->data, 'id');
    }

    public function getEmail(): string
    {
        return $this->getValueByKey($this->data, 'email');
    }

    public function getName(): string
    {
        return sprintf(
            '%s %s',
            $this->getFirstName(),
            $this->getLastName()
        );
    }

    public function getFirstName(): string
    {
        return $this->getValueByKey($this->data, 'first_name');
    }

    public function getLastName(): string
    {
        return $this->getValueByKey($this->data, 'last_name');
    }

    public function getAvatarUrl(): string
    {
        return $this->getValueByKey($this->data, 'avatar');
    }

    public function getPhone(): string
    {
        return $this->getValueByKey($this->data, 'phone');
    }

    public function toArray(): array
    {
        return $this->data;
    }
}
