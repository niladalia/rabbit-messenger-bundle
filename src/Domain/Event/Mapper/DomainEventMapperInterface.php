<?php

namespace RabbitMessengerBundle\Domain\Event\Mapper;

use RabbitMessengerBundle\Domain\Event\Mapper\DTO\DomainEventMappingDTO;

interface DomainEventMapperInterface
{
    public function map(array $data): DomainEventMappingDTO;
}
