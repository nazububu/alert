<?php

namespace App\Repository;

use App\Entity\Subscriber;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Subscriber>
 *
 * @method Subscriber|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subscriber|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subscriber[]    findAll()
 * @method Subscriber[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubscriberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subscriber::class);
    }

    /**
     * @param int $regionId
     *
     * @return ArrayCollection<Subscriber>
     */
    public function findByRegionId(int $regionId): ArrayCollection
    {
        return new ArrayCollection(
            $this
                ->createQueryBuilder('subscribers')
                ->andWhere('subscribers.regions LIKE :region')
                ->setParameter('region', "%$regionId%")
                ->getQuery()
                ->execute()
        );
    }
}
