<?php

namespace App\Repository;

use App\Core\Doctrine\Criteria\CriteriaParser;
use App\Entity\Reply;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Reply|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reply|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reply[]    findAll()
 * @method Reply[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReplyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Reply::class);
    }

    /**
     * @param array $criteria
     * @return array
     * @throws QueryException
     */
    public function getReplyList($criteria = [])
    {
        $qb = new QueryBuilder($this->_em);

        $qb->select("r")
            ->from(Reply::class, "r")
            ->leftJoin(
                "r.comment", "comment"
            );

        $criteriaParser = new CriteriaParser($qb, $criteria, $this->getMapper());
        $criteriaParser->parse();

        return $qb->getQuery()->getResult();
    }

    /**
     * @return array
     */
    protected function getMapper()
    {
        return [
            'id' => 'r.id',
            'creationDate' => 'r.creationDate',
            'commentId' => 'comment.id'
        ];
    }
}
