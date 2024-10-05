<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class StatisticDto
{
    #[Assert\Regex(pattern: '/^(?=.{1,255}$)(?!-)[A-Za-z0-9-]{1,63}(?<!-)(\.[A-Za-z]{2,})+$/', message: 'domain не валиден')]
    private string $domain = '';

    #[Assert\DateTime(message: 'dateFrom не валиден (формат: Y-m-d H:i:s)')]
    private ?string $dateFrom = null;

    #[Assert\DateTime(message: 'dateTo не валиден (формат: Y-m-d H:i:s)')]
    private ?string $dateTo = null;

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function setDomain(string $domain): self
    {
        $this->domain = $domain;

        return $this;
    }

    public function getDateFrom(): ?string
    {
        return $this->dateFrom;
    }

    public function setDateFrom(?string $dateFrom): self
    {
        $this->dateFrom = $dateFrom;

        return $this;
    }

    public function getDateTo(): ?string
    {
        return $this->dateTo;
    }

    public function setDateTo(?string $dateTo): self
    {
        $this->dateTo = $dateTo;

        return $this;
    }
}
