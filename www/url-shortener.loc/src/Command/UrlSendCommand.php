<?php

namespace App\Command;

use App\Entity\Url;
use App\Repository\UrlRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(name: 'app:url-send', description: 'Отправка новых url')]
class UrlSendCommand extends Command
{
    public function __construct(
        private readonly string $targetEndpoint,
        private readonly UrlRepository $urlRepository,
        private readonly HttpClientInterface $client,
    ) {
        parent::__construct();
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        /** @var Url[] $newUrls */
        $newUrls = $this->urlRepository->findByNotSent();

        $data = ['urls' => []];
        foreach ($newUrls as $newUrl) {
            $data['urls'][] = [
                'url' => $newUrl->getUrl(),
                'createdDate' => $newUrl->getCreatedDate()->format('Y-m-d H:i:s'),
            ];
            $newUrl->setSent(true);
        }

        $this->urlRepository->commit();

        try {
            $this->client->request('POST', $this->targetEndpoint, ['json' => $data]);
        } catch (ClientExceptionInterface $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $responseContent = json_decode($e->getResponse()->getContent(false), true);
            $io->error('Cтатус-код '.$statusCode.'. '.$responseContent['message'] ?? '');

            return Command::FAILURE;
        }

        $io->success('Новые url отправлены');

        return Command::SUCCESS;
    }
}
