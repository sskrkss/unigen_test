<?php

namespace App\Repository;

use App\Entity\HandledUrl;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method HandledUrl|null find($id, $lockMode = null, $lockVersion = null)
 * @method HandledUrl|null findOneBy(array $criteria, array $orderBy = null)
 * @method HandledUrl[]    findAll()
 * @method HandledUrl[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HandledUrlRepository extends ServiceEntityRepository
{
    use RepositoryModifyTrait;

    public function __construct(ManagerRegistry $registry, private readonly Connection $connection)
    {
        parent::__construct($registry, HandledUrl::class);
    }

    public function countUniqueUrls(?string $from, ?string $to): int
    {
        $query = $this->createQueryBuilder('u')
            ->select('COUNT(DISTINCT u.url)');

        if (null !== $from) {
            $query
                ->andWhere('u.addDate >= :from')
                ->setParameter('from', $from);
        }

        if (null !== $to) {
            $query
                ->andWhere('u.addDate <= :to')
                ->setParameter('to', $to);
        }

        return (int) $query->getQuery()->getSingleScalarResult();
    }

    public function countUniqueDomain(string $domain): int
    {
        $sql = '
            SELECT COUNT(DISTINCT url) AS unique_urls_count
            FROM handled_url
            WHERE url REGEXP :domain
        ';

        $query = $this->connection->prepare($sql);
        $query->bindValue('domain', '^https?://(.*\\.)?'.preg_quote($domain, '/').'(/|$)');

        return (int) $query->executeQuery()->fetchOne();
    }
}
