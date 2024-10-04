<?php

namespace App\Service;

use App\Entity\Url;
use App\Exception\UrlExpiredException;
use App\Exception\UrlNotFoundException;
use App\Model\DecodeUrlDto;
use App\Model\EncodeUrlDto;
use App\Repository\UrlRepository;

class UrlService
{
    public function __construct(
        private readonly UrlRepository $urlRepository,
    ) {
    }

    public function encode(EncodeUrlDto $encodeUrlDto): string
    {
        $url = $this->urlRepository->findOneByUrl($encodeUrlDto->getUrl());

        if (null === $url) {
            $url = (new Url())->setUrl($encodeUrlDto->getUrl());
            $this->urlRepository->saveAndCommit($url);
        }

        return $url->getHash();
    }

    public function decode(DecodeUrlDto $decodeUrlDto): string
    {
        $url = $this->urlRepository->findOneByHash($decodeUrlDto->getHash());

        if (null === $url) {
            throw new UrlNotFoundException();
        }

        if ($url->getExpireDate() < new \DateTimeImmutable()) {
            throw new UrlExpiredException();
        }

        return $url->getUrl();
    }
}
