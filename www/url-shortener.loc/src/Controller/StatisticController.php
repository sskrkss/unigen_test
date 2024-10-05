<?php

namespace App\Controller;

use App\Model\StatisticDto;
use App\Resolver\RequestQueryResolver;
use App\Service\StatisticService;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Annotation\Route;

class StatisticController extends AbstractController
{
    public function __construct(
        private readonly StatisticService $statisticService,
    ) {
    }

    #[OA\Tag(name: 'statistic')]
    #[OA\Response(response: 200, description: 'Получение статистики по записанным url')]
    #[Route('/api/v1/statistics', name: 'statistic', methods: 'GET')]
    public function statistic(#[MapQueryString(resolver: RequestQueryResolver::class)] StatisticDto $statisticDto): JsonResponse
    {
        return $this->json($this->statisticService->getStatistic($statisticDto));
    }
}
