<?php

namespace RabbitMessengerBundle\Domain\Event;

interface DomainEventPublisherInterface
{
    public function publish(DomainEvent ...$domainEvents): void;
}
