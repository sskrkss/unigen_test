<?php

namespace App\Controller;

use App\Model\HandleDto;
use App\Resolver\RequestBodyResolver;
use App\Service\HandleService;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class HandleController extends AbstractController
{
    public function __construct(
        private readonly HandleService $handleService,
    ) {
    }

    #[OA\Tag(name: 'handle')]
    #[OA\Response(response: 200, description: 'Прием массива url и запись в бд')]
    #[Route('/api/v1/handle', name: 'handle', methods: 'POST')]
    public function handle(#[MapRequestPayload(resolver: RequestBodyResolver::class)] HandleDto $handleDto): JsonResponse
    {
        return $this->json($this->handleService->urlHandle($handleDto));
    }
}
