<?php

namespace RabbitMessengerBundle\Infrastructure;


use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\MessageBusInterface;
use RabbitMessengerBundle\Domain\Event\DomainEvent;
use RabbitMessengerBundle\Domain\Event\DomainEventPublisherInterface;

final class RabbitDomainEventPublisher implements DomainEventPublisherInterface
{
    public const AMQP_NOPARAM = 0;
    public const AMQP_DURABLE = 2;

    public function __construct(private MessageBusInterface $bus, private LoggerInterface $logger)
    {
    }

    public function publish(DomainEvent ...$domainEvents): void
    {
        foreach ($domainEvents as $domainEvent) {

            $this->logger->info(sprintf("Publishing event with binding key : %s", $domainEvent::eventName()));
            
            $routingKey = $domainEvent::eventName();
            $amqpStamp = [
                new AmqpStamp(
                    $routingKey,
                    self::AMQP_NOPARAM,
                    [
                        'content_type' => 'application/json',
                        'content_encoding' => 'utf-8',
                        'headers' => ['Content-Type' => 'application/json'],
                        'delivery_mode' => self::AMQP_DURABLE,
                        'priority' => 0,
                        'message_id' => $domainEvent->eventId(),
                        'timestamp' => $domainEvent->occurredOn(),
                        'type' => $domainEvent::eventName()
                    ]
                )
            ];

            $this->bus->dispatch($domainEvent, $amqpStamp);
        }
    }
}
