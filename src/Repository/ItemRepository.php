<?php

namespace App\Repository;

use App\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Item>
 */
class ItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    public function findAllByUser(array $criteria = []): array
    {
        $qb = $this->createQueryBuilder('i')
            ->addSelect('u')
            ->join('i.createdBy', 'u');

        foreach ($criteria as $field => $value) {
            $qb->andWhere(sprintf('i.%s = :%s', $field, $field))
                ->setParameter($field, $value);
        }

        return $qb->getQuery()->getResult();
    }
}
