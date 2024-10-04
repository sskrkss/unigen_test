<?php

namespace App\Controller;

use App\Model\DecodeUrlDto;
use App\Model\EncodeUrlDto;
use App\Resolver\RequestBodyResolver;
use App\Resolver\RequestQueryResolver;
use App\Service\UrlService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class UrlController extends AbstractController
{
    public function __construct(
        private readonly UrlService $urlService,
    ) {
    }

    #[Route('/api/v1/encode-url', name: 'encode_url', methods: 'POST')]
    #[OA\Tag(name: 'url-shortener')]
    #[OA\Response(response: 400, description: 'Ошибка валидации')]
    public function encodeUrl(#[MapRequestPayload(resolver: RequestBodyResolver::class)] EncodeUrlDto $encodeUrlDto): JsonResponse
    {
        return $this->json(['hash' => $this->urlService->encode($encodeUrlDto)]);
    }

    #[Route('/api/v1/decode-url', name: 'decode_url', methods: 'GET')]
    #[OA\Tag(name: 'url-shortener')]
    #[OA\Response(response: 400, description: 'Ошибка валидации')]
    #[OA\Response(response: 404, description: 'Url не найден')]
    #[OA\Response(response: 410, description: 'Срок действия закодированного url истек (15 сек)')]
    public function decodeUrl(#[MapQueryString(resolver: RequestQueryResolver::class)] DecodeUrlDto $decodeUrlDto): JsonResponse
    {
        return $this->json(['url' => $this->urlService->decode($decodeUrlDto)]);
    }

    #[Route('/api/v1/redirect-decode-url', name: 'redirect-decode_url', methods: 'GET')]
    #[OA\Tag(name: 'url-shortener')]
    #[OA\Response(response: 400, description: 'Ошибка валидации')]
    #[OA\Response(response: 404, description: 'Url не найден')]
    #[OA\Response(response: 410, description: 'Срок действия закодированного url истек (15 сек)')]
    public function redirectDecodeUrl(#[MapQueryString(resolver: RequestQueryResolver::class)] DecodeUrlDto $decodeUrlDto): RedirectResponse
    {
        return $this->redirect('/'.$this->urlService->decode($decodeUrlDto));
    }

    #[OA\Tag(name: 'url-shortener')]
    #[Route('/api/v1/command-test', name: 'command_test', methods: 'POST')]
    public function commandTest(Request $request): JsonResponse
    {
        return $this->json(['sentUrl' => json_decode($request->getContent())]);
    }
}
