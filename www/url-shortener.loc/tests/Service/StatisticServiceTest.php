<?php

namespace Service;

use App\Model\StatisticDto;
use App\Repository\HandledUrlRepository;
use App\Service\StatisticService;
use App\Tests\AbstractTestCase;
use PHPUnit\Framework\MockObject\MockObject;

class StatisticServiceTest extends AbstractTestCase
{
    private HandledUrlRepository|MockObject $statisticRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->statisticRepository = $this->createMock(HandledUrlRepository::class);
    }

    private function createService(): StatisticService
    {
        return new StatisticService($this->statisticRepository);
    }

    public function testGetStatistic()
    {
        $this->statisticRepository->expects($this->once())
            ->method('countUniqueUrls')
            ->with('2024-10-05 16:32:45', '2024-10-05 16:47:12')
            ->willReturn(2);

        $this->statisticRepository->expects($this->once())
            ->method('countUniqueDomain')
            ->with('testDomain')
            ->willReturn(7);

        $statisticDto = (new StatisticDto())
            ->setDomain('testDomain')
            ->setDateFrom('2024-10-05 16:32:45')
            ->setDateTo('2024-10-05 16:47:12');

        $this->assertEquals(
            [
                'Количество уникальных url за заданный промежуток времени' => 2,
                'Количество уникальных url с указанным доменом' => 7,
            ], $this->createService()->getStatistic($statisticDto)
        );
    }
}
