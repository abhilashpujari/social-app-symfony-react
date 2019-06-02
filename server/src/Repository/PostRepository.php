<?php

namespace App\Repository;

use App\Core\Doctrine\Criteria\CriteriaParser;
use App\Core\Doctrine\Pagination;
use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @param array $criteria
     * @param Pagination|null $pagination
     * @return array
     */
    public function getPostList($criteria = [], Pagination $pagination = null)
    {
        $qb = new QueryBuilder($this->_em);

        $qb->select("p")
            ->from(Post::class, "p");

        $query = $qb->getQuery();

        $criteriaParser = new CriteriaParser($qb, $criteria, $this->getMapper());
        $criteriaParser->parse();

        return ($pagination)
            ? $pagination->getPaginationData($query, false)
            : ['result' => $query->getResult()];
    }

    /**
     * @return array
     */
    protected function getMapper()
    {
        return [
            'id' => 'p.id',
            'creationDate' => 'p.creationDate'
        ];
    }
}
