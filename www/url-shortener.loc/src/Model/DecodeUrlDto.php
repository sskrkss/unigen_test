<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class DecodeUrlDto
{
    #[Assert\NotBlank(message: 'hash не должен быть пустым')]
    #[Assert\Regex(pattern: '/^\d{14}$/', message: 'hash должен состоять ровно из 14 цифр')]
    private string $hash;

    public function getHash(): string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }
}
