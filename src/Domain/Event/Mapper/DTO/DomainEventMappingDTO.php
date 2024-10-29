<?php

namespace RabbitMessengerBundle\Domain\Event\Mapper\DTO;

class DomainEventMappingDTO
{
    public function __construct(
        private string $class,
        private array $map

    )
    {
    }

    public function map(): array
    {
        return $this->map;
    }

    public function class(): string
    {
        return $this->class;
    }
}
