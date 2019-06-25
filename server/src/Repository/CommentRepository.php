<?php

namespace App\Repository;

use App\Core\Doctrine\Criteria\CriteriaParser;
use App\Core\Doctrine\Pagination;
use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
     * @param Pagination $pagination
     * @return mixed
     */
    public function getCommentList($criteria = [], Pagination $pagination = null)
    {
        $qb = new QueryBuilder($this->_em);

        $qb->select("c")
            ->from(
                Comment::class,
                "c"
            )
            ->leftJoin(
                "c.post",
                "post"
            );

        $criteriaParser = new CriteriaParser($qb, $criteria, $this->getMapper());
        $criteriaParser->parse();

        $query = $qb->getQuery();

        return ($pagination)
            ? $pagination->getPaginationData($query, true)
            : ['result' => $query->getResult()];
    }

    /**
     * @return array
     */
    protected function getMapper()
    {
        return [
            'id' => 'c.id',
            'creationDate' => 'c.creationDate',
            'postId' => 'post.id'
        ];
    }
}
