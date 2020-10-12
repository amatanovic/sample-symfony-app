<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\PostLike;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class LikeRepository
 * @package App\Repository
 */
class LikeRepository extends ServiceEntityRepository
{
    /**
     * LikeRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostLike::class);
    }
}
