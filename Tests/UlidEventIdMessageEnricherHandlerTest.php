<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Symfony\EventSourcing\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Bridge\Symfony\EventSourcing\UlidEventIdMessageEnricherHandler;
use SonsOfPHP\Component\EventSourcing\Message\Enricher\Handler\MessageEnricherHandlerInterface;
use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;
use SonsOfPHP\Component\EventSourcing\Metadata;
use Symfony\Component\Uid\Ulid;

/**
 * @coversDefaultClass \SonsOfPHP\Bridge\Symfony\EventSourcing\UlidEventIdMessageEnricherHandler
 */
final class UlidEventIdMessageEnricherHandlerTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheRightInterface(): void
    {
        $handler = new UlidEventIdMessageEnricherHandler();

        $this->assertInstanceOf(MessageEnricherHandlerInterface::class, $handler);
    }

    /**
     * @covers ::enrich
     */
    public function testItWillGenerateUlid(): void
    {
        $handler = new UlidEventIdMessageEnricherHandler();

        $message = $this->createMock(MessageInterface::class);
        $message->expects($this->once())
            ->method('withMetadata')
            ->with($this->callback(function ($metadata) {
                return Ulid::isValid($metadata[Metadata::EVENT_ID]);
            }))
        ;

        $handler->enrich($message);
    }
}
