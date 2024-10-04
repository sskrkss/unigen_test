<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class DecodeUrlDto
{
    #[Assert\NotBlank(message: 'hash не должен быть пустым')]
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
