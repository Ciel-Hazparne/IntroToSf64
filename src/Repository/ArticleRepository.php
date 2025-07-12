<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 */
class ArticleRepository extends ServiceEntityRepository
{


    private EntityManagerInterface $em;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Article::class);
        $this->em = $em;
    }

    public function searchArticle(?float $minPrice, ?float $maxPrice, ?Category $category): array
    {
        $qb = $this->createQueryBuilder('a');

        if ($minPrice !== null) {
            $qb->andWhere('a.price >= :minPrice')
                ->setParameter('minPrice', $minPrice);
        }

        if ($maxPrice !== null) {
            $qb->andWhere('a.price <= :maxPrice')
                ->setParameter('maxPrice', $maxPrice);
        }

        if ($category !== null) {
            $qb->andWhere('a.category = :category')
                ->setParameter('category', $category);
        }

        return $qb->orderBy('a.name', 'ASC')->getQuery()->getResult();
    }

    public function findArticleByName($keyword){
        return $this->createQueryBuilder('a')
            ->where('a.name like :name')
            ->setParameter('name', '%'.$keyword.'%')
            ->orderBy('a.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
    public function save(Article $entity, bool $flush = false): void
    {
        $this->em->persist($entity);

        if ($flush) {
            $this->em->flush();
        }
    }

    public function remove(Article $entity, bool $flush = false): void
    {
        $this->em->remove($entity);

        if ($flush) {
            $this->em->flush();
        }
    }
}
