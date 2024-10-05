<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class HandleDto
{
    #[Assert\All([
        new Assert\Type(type: 'array', message: 'Элементом массива должен быть массив'),
        new Assert\Collection(
            fields: [
                'url' => [
                    new Assert\NotBlank(message: 'Один из url не должен быть пустым'),
                    new Assert\Url(message: 'Один из url не валиден'),
                ],
                'createdDate' => [
                    new Assert\NotBlank(message: 'Один из createdDate пустой'),
                    new Assert\DateTime(message: 'Один из createdDate не валиден (формат: Y-m-d H:i:s)'),
                ],
            ],
            missingFieldsMessage: 'Вложенные массивы должны содержать 2 ключа: url, createdDate'
        ),
    ])]
    private array $urls = [];

    public function getUrls(): array
    {
        return $this->urls;
    }

    public function setUrls(array $urls): void
    {
        $this->urls = $urls;
    }
}
