<?php

namespace App\Entity;

use App\Repository\UrlRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UrlRepository::class)]
class Url
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $url;

    #[ORM\Column(type: Types::STRING, length: 14)]
    private string $hash;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $createdDate;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $expireDate;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $sent = false;

    public function __construct()
    {
        $createdDate = new \DateTimeImmutable();
        $expireDate = (new \DateTimeImmutable())->modify('+15 seconds');

        $this->setCreatedDate($createdDate);
        $this->setExpireDate($expireDate);
        $this->setHash($createdDate->format('YmdHis'));
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function getCreatedDate(): \DateTimeImmutable
    {
        return $this->createdDate;
    }

    public function setCreatedDate(\DateTimeImmutable $createdDate): self
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    public function getExpireDate(): \DateTimeImmutable
    {
        return $this->expireDate;
    }

    public function setExpireDate(\DateTimeImmutable $expireDate): self
    {
        $this->expireDate = $expireDate;

        return $this;
    }

    public function isSent(): bool
    {
        return $this->sent;
    }

    public function setSent(bool $sent): self
    {
        $this->sent = $sent;

        return $this;
    }
}
