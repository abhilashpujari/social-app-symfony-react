<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    /**
     * @param array $criteria
     * @return array
     * @throws QueryException
     */
    public function getCommentList($criteria = [])
    {
        $qb = new QueryBuilder($this->_em);

        $qb->select("c")
            ->from(Comment::class, "c");

        //$criteriaParser = new CriteriaParser($qb, $criteria, $this->getMapper());
        //$criteriaParser->parse();

        return $qb->getQuery()->getResult();
    }

    /**
     * @return array
     */
    protected function getMapper()
    {
        return [
        ];
    }
}
