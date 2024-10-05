<?php

namespace App\Service;

use App\Model\StatisticDto;
use App\Repository\HandledUrlRepository;

class StatisticService
{
    public function __construct(
        private readonly HandledUrlRepository $handledUrlRepository,
    ) {
    }

    public function getStatistic(StatisticDto $statisticDto): array
    {
        $countUniqueUrls = $this->handledUrlRepository->countUniqueUrls($statisticDto->getDateFrom(), $statisticDto->getDateTo());
        $countUniqueDomain = $this->handledUrlRepository->countUniqueDomain($statisticDto->getDomain());

        return [
            'Количество уникальных url за заданный промежуток времени' => $countUniqueUrls,
            'Количество уникальных url с указанным доменом' => $countUniqueDomain,
        ];
    }
}
