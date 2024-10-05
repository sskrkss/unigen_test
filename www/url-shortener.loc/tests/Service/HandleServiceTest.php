<?php

namespace Service;

use App\Model\HandleDto;
use App\Repository\HandledUrlRepository;
use App\Service\HandleService;
use App\Tests\AbstractTestCase;
use PHPUnit\Framework\MockObject\MockObject;

class HandleServiceTest extends AbstractTestCase
{
    private HandledUrlRepository|MockObject $handledUrlRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->handledUrlRepository = $this->createMock(HandledUrlRepository::class);
    }

    private function createService(): HandleService
    {
        return new HandleService($this->handledUrlRepository);
    }

    public function testUrlHandle()
    {
        $this->handledUrlRepository
            ->expects($this->exactly(2))
            ->method('save')
            ->with($this->logicalOr(
                $this->callback(fn ($arg) => 'testUrl1' === $arg->getUrl() && $arg->getCreatedDate() instanceof \DateTimeImmutable),
                $this->callback(fn ($arg) => 'testUrl2' === $arg->getUrl() && $arg->getCreatedDate() instanceof \DateTimeImmutable)
            ));

        $this->handledUrlRepository
            ->expects($this->once())
            ->method('commit');

        $urlData = [
            ['url' => 'testUrl1', 'createdDate' => '2024-10-05 15:30:00'],
            ['url' => 'testUrl2', 'createdDate' => '2024-10-05 16:30:00'],
        ];

        $handleDto = new HandleDto();
        $handleDto->setUrls($urlData);

        $result = $this->createService()->urlHandle($handleDto);

        $this->assertEquals(['message' => 'Url добавлены'], $result);
    }
}
