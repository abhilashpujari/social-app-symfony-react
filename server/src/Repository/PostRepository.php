<?php

namespace App\Repository;

use App\Core\Doctrine\Criteria\CriteriaParser;
use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\QueryException;
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
     * @return array
     * @throws QueryException
     */
    public function getPostList($criteria = [])
    {
        $qb = new QueryBuilder($this->_em);

        $qb->select("p")
            ->from(Post::class, "p");

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
            'id' => 'p.id',
            'creationDate' => 'p.creationDate'
        ];
    }
}
