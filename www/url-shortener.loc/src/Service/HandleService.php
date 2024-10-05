<?php

namespace App\Service;

use App\Entity\HandledUrl;
use App\Model\HandleDto;
use App\Repository\HandledUrlRepository;

class HandleService
{
    public function __construct(
        private readonly HandledUrlRepository $handledUrlRepository,
    ) {
    }

    public function urlHandle(HandleDto $handleDto): array
    {
        foreach ($handleDto->getUrls() as $urlDto) {
            $handledUrl = (new HandledUrl())
                ->setUrl($urlDto['url'])
                ->setCreatedDate(new \DateTimeImmutable($urlDto['createdDate']));
            $this->handledUrlRepository->save($handledUrl);
        }

        $this->handledUrlRepository->commit();

        return ['message' => 'Url добавлены'];
    }
}
