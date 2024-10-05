<?php

namespace App\Controller;

use App\Model\DecodeUrlDto;
use App\Model\EncodeUrlDto;
use App\Resolver\RequestBodyResolver;
use App\Resolver\RequestQueryResolver;
use App\Service\UrlService;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class UrlController extends AbstractController
{
    public function __construct(
        private readonly UrlService $urlService,
    ) {
    }

    #[Route('/api/v1/url/encode', name: 'encode_url', methods: 'POST')]
    #[OA\Tag(name: 'url-shortener')]
    #[OA\Response(response: 200, description: 'Запись и получение hash url')]
    #[OA\Response(response: 400, description: 'Ошибка валидации')]
    public function encodeUrl(#[MapRequestPayload(resolver: RequestBodyResolver::class)] EncodeUrlDto $encodeUrlDto): JsonResponse
    {
        return $this->json(['hash' => $this->urlService->encode($encodeUrlDto)]);
    }

    #[Route('/api/v1/url/decode', name: 'decode_url', methods: 'GET')]
    #[OA\Tag(name: 'url-shortener')]
    #[OA\Response(response: 200, description: 'Получение url по его hash')]
    #[OA\Response(response: 400, description: 'Ошибка валидации')]
    #[OA\Response(response: 404, description: 'Url не найден')]
    #[OA\Response(response: 410, description: 'Срок действия закодированного url истек (15 сек)')]
    public function decodeUrl(#[MapQueryString(resolver: RequestQueryResolver::class)] DecodeUrlDto $decodeUrlDto): JsonResponse
    {
        return $this->json(['url' => $this->urlService->decode($decodeUrlDto)]);
    }

    #[Route('/api/v1/url/decode/redirect', name: 'redirect_decode_url', methods: 'GET')]
    #[OA\Tag(name: 'url-shortener')]
    #[OA\Response(response: 200, description: 'Редирект на url по его hash')]
    #[OA\Response(response: 400, description: 'Ошибка валидации')]
    #[OA\Response(response: 404, description: 'Url не найден')]
    #[OA\Response(response: 410, description: 'Срок действия закодированного url истек (15 сек)')]
    public function redirectDecodeUrl(#[MapQueryString(resolver: RequestQueryResolver::class)] DecodeUrlDto $decodeUrlDto): RedirectResponse
    {
        return $this->redirect($this->urlService->decode($decodeUrlDto));
    }
}
