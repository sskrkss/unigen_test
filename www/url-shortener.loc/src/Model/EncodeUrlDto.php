<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class EncodeUrlDto
{
    #[Assert\NotBlank(message: 'url не должен быть пустым')]
    private string $url;

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }
}
