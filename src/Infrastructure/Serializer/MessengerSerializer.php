<?php

namespace RabbitMessengerBundle\Infrastructure\Serializer;

use RabbitMessengerBundle\Domain\Event\Mapper\DomainEventMapperInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

final class MessengerSerializer implements  SerializerInterface
{


    public function __construct(private DomainEventMapperInterface $mapper)
    {

    }

    public function decode(array $encodedEnvelope): Envelope
    {
        $decodedEnvelope =  json_decode($encodedEnvelope['body'], true);

        $mapDataResponse = $this->mapper->map($decodedEnvelope);

        $className =  $mapDataResponse->class();

        return new Envelope($className::deserialize(...$mapDataResponse->map()));
    }

    public function encode(Envelope $envelope): array
    {
        $message = $envelope->getMessage();

        return [
            'body' => json_encode([
                'event_id' => $message->eventId(),
                'aggregate_id' => $message->aggregateId(),
                'type' => $message->eventName(),
                'attributes' => $message->serialize(),
                'occurred_on' => $message->occurredOn()
            ])
        ];
    }
}
