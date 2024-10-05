<?php

namespace Service;

use App\Entity\Url;
use App\Exception\UrlExpiredException;
use App\Exception\UrlNotFoundException;
use App\Model\DecodeUrlDto;
use App\Model\EncodeUrlDto;
use App\Repository\UrlRepository;
use App\Service\UrlService;
use App\Tests\AbstractTestCase;
use PHPUnit\Framework\MockObject\MockObject;

class UrlServiceTest extends AbstractTestCase
{
    private UrlRepository|MockObject $urlRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->urlRepository = $this->createMock(UrlRepository::class);
    }

    private function createService(): UrlService
    {
        return new UrlService($this->urlRepository);
    }

    public function testEncode()
    {
        $expectedUrl = (new Url())->setUrl('testUrl');
        $this->setEntityId($expectedUrl, 3);

        $this->urlRepository->expects($this->once())
            ->method('findOneByUrl')
            ->with('testUrl')
            ->willReturn($expectedUrl);

        $encodeUrlDto = (new EncodeUrlDto())->setUrl('testUrl');

        $this->assertEquals($expectedUrl->getHash(), $this->createService()->encode($encodeUrlDto));
    }

    public function testEncodeUrlNull()
    {
        $this->urlRepository->expects($this->once())
            ->method('findOneByUrl')
            ->with('testUrl')
            ->willReturn(null);

        $expectedUrl = (new Url())->setUrl('testUrl');

        $this->urlRepository->expects($this->once())
            ->method('saveAndCommit');

        $encodeUrlDto = (new EncodeUrlDto())->setUrl('testUrl');

        $this->assertEquals($expectedUrl->getHash(), $this->createService()->encode($encodeUrlDto));
    }

    public function testDecode()
    {
        $expectedUrl = (new Url())
            ->setUrl('testUrl')
            ->setHash('20241005165121');
        $this->setEntityId($expectedUrl, 3);

        $this->urlRepository->expects($this->once())
            ->method('findOneByHash')
            ->with('20241005165121')
            ->willReturn($expectedUrl);

        $decodeUrlDto = (new DecodeUrlDto())->setHash('20241005165121');

        $this->assertEquals($expectedUrl->getUrl(), $this->createService()->decode($decodeUrlDto));
    }

    public function testDecodeUrlNotFound()
    {
        $this->expectException(UrlNotFoundException::class);

        $this->urlRepository->expects($this->once())
            ->method('findOneByHash')
            ->with('20241005165121')
            ->willReturn(null);

        $decodeUrlDto = (new DecodeUrlDto())->setHash('20241005165121');

        $this->createService()->decode($decodeUrlDto);
    }

    public function testDecodeUrlExpired()
    {
        $this->expectException(UrlExpiredException::class);

        $expectedUrl = (new Url())
            ->setUrl('testUrl')
            ->setHash('20241005165121');
        $expectedUrl->setExpireDate($expectedUrl->getExpireDate()->modify('-1 day'));
        $this->setEntityId($expectedUrl, 3);

        $this->urlRepository->expects($this->once())
            ->method('findOneByHash')
            ->with('20241005165121')
            ->willReturn($expectedUrl);

        $decodeUrlDto = (new DecodeUrlDto())->setHash('20241005165121');

        $this->createService()->decode($decodeUrlDto);
    }
}
