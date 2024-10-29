# Rabbit Messenger Bundle

This repository contains a simple template for building event-driven microservices using the Rabbit Messenger Bundle.

## Requirements

You need to have a running RabbitMQ configuration or a Docker setup to use this bundle effectively.

## Installation

To integrate the Rabbit Messenger Bundle into your project, run the following command:

```bash
composer update niladalia/rabbit-messenger-bundle
```
# Usage

## Domain Events

### Create Domain Events
Your domain event classes should extend the `DomainEvent` class provided by the bundle:

```php
namespace RabbitMessengerBundle\Domain\Event;

class YourDomainEvent extends DomainEvent {
    // Your event properties and methods
}
```
## Publishing Events

To publish events, inject the `DomainEventPublisherInterface` into the classes where you need to publish events. Call the `publish` method as shown below:

```php
use RabbitMessengerBundle\Domain\Event\DomainEventPublisherInterface;

class YourService {
private $eventPublisher;

    public function __construct(DomainEventPublisherInterface $eventPublisher) {
        $this->eventPublisher = $eventPublisher;
    }

    public function someMethod() {
        $event = new YourDomainEvent(/* event data */);
        $this->eventPublisher->publish($event);
    }
}
```

## Configuration

### Messenger Configuration

In the `messenger.yaml` file of both your sender application and listener, specify the serializer as follows:

```yaml
  serializer: RabbitMessengerBundle\Infrastructure\Serializer\MessengerSerializer
```

### Event Listener Configuration

#### Implement the Domain Event Mapper

Your listener or receiver micro should implement the `DomainEventMapperInterface`. 

This interface is responsible for mapping event types to the corresponding event classes in your microservice.

Your implementation must return an array with the following keys:

```php
$map = [
    'aggregateId'
    'eventId'
    'attributes'
    'occurredOn'
];
```
It will also need to return a string with the full path for the domain event class of the recieved event from Rabbit.

#### Service Configuration

In the `services.yaml` file, specify the implementation for the `DomainEventMapperInterface` and ensure it is public:

```php
App\Shared\Infrastructure\Event\DomainEventMapper:
    public: true  # Ensure the mapper is accessible

RabbitMessengerBundle\Domain\Event\Mapper\DomainEventMapperInterface: '@App\Shared\Infrastructure\Event\DomainEventMapper'
```
