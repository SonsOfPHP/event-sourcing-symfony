<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Symfony\EventSourcing\Tests\Message;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Bridge\Symfony\EventSourcing\Message\MessageNormalizer;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use SonsOfPHP\Component\EventSourcing\Message\AbstractGenericMessage;

/**
 * @coversDefaultClass \SonsOfPHP\Bridge\Symfony\EventSourcing\Message\MessageNormalizer
 */
final class MessageNormalizerTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheRightInterface(): void
    {
        $normalizer = new MessageNormalizer();

        $this->assertInstanceOf(DenormalizerInterface::class, $normalizer);
        $this->assertInstanceOf(NormalizerInterface::class, $normalizer);
    }

    /**
     * @covers ::normalize
     * @covers ::supportsNormalization
     */
    public function testItWillNormalizeMessage(): void
    {
        $normalizer = new MessageNormalizer();

        $message = $this->createStub(AbstractGenericMessage::class);

        $this->assertTrue($normalizer->supportsNormalization($message));

        $output = $normalizer->normalize($message);

        $this->assertArrayHasKey('payload', $output);
        $this->assertArrayHasKey('metadata', $output);
    }

    /**
     * @covers ::denormalize
     * @covers ::supportsDenormalization
     */
    public function testItWillDenormalizeMessage(): void
    {
        $normalizer = new MessageNormalizer();

        $data = [
            'payload'  => [
                'unit' => 'test',
            ],
            'metadata' => [
                'test' => 'unit',
            ],
        ];
        $type = StubMessage::class;

        $this->assertTrue($normalizer->supportsDenormalization($data, $type));

        $output = $normalizer->denormalize($data, $type);

        $this->assertSame('test', $output->getPayload()['unit']);
        $this->assertSame('unit', $output->getMetadata()['test']);
    }
}

class StubMessage extends AbstractGenericMessage
{
}