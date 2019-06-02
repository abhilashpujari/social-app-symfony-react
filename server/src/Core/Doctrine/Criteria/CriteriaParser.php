<?php

namespace App\Core\Doctrine\Criteria;

use Doctrine\ORM\QueryBuilder;
use Exception;

/**
 * Class CriteriaParser
 * @package App\Core\Doctrine\Criteria
 */
class CriteriaParser
{

    /**
     * @var
     */
    protected $criteria;
    /**
     * @var
     */
    protected $mapper;
    /**
     * @var QueryBuilder
     */
    protected $qb;

    /**
     * @param QueryBuilder $qb
     * @param $criteria
     * @param $mapper
     */
    public function __construct(QueryBuilder $qb, $criteria, $mapper)
    {
        $this->criteria = $criteria;
        $this->mapper = $mapper;
        $this->qb = $qb;
    }

    /**
     * @return QueryBuilder
     * @throws Exception
     */
    public function parse()
    {
        $this->parseAnd();
        $this->parseOrderBy();

        return $this->qb;
    }

    /**
     * @throws Exception
     */
    protected function parseAnd()
    {
        if (isset($this->criteria['and'])) {
            $andX = $this->qb->expr()->andX();
            foreach($this->criteria['and'] as $criteriaString) {
                preg_match('/^([A-Za-z\.]+)\s(lt|lte|gt|gte|eq|neq|like)\s(.+)$/i', $criteriaString, $parts);
                if (isset($parts[1]) && isset($parts[2]) && isset($parts[3])) {
                    $key = $this->mapper[$parts[1]];
                    $operation = $parts[2];
                    $value = $parts[3];

                    if ($this->checkMapperKeyExist($parts[1])) {
                        $andX->add($this->qb->expr()->{$operation}($key, ":$parts[1]"));
                        $this->qb->setParameter($parts[1], $value);
                    }

                } else {
                    throw new Exception("Invalid criteria AND string: $criteriaString", 400);
                }
            }

            // check for parts length
            if ($andX->getParts()) {
                $this->qb->andWhere($andX);
            }
        }
    }

    /**
     * @throws Exception
     */
    protected function parseOrderBy()
    {
        if (isset($this->criteria['order'])) {
            foreach($this->criteria['order'] as $orderByString) {
                preg_match('/^([A-Za-z\.]+)\s(DESC|ASC)$/i', $orderByString, $parts);

                if (isset($parts[1]) && isset($parts[2])) {
                    if ($this->checkMapperKeyExist($parts[1])) {
                        $this->qb->addOrderBy($this->mapper[$parts[1]], strtoupper($parts[2]));
                    }
                } else {
                    throw new Exception("Invalid criteria ORDER string: {$orderByString}", 400);
                }
            }
        }
    }

    /**
     * @param $key
     * @return bool
     * @throws Exception
     */
    protected function checkMapperKeyExist($key)
    {

        if (array_key_exists($key, $this->mapper)) {
            return true;
        } else {
            throw new Exception("Invalid mapper key $key. Valid key mapper are" . implode(',', array_keys($this->mapper)), 400);
        }
    }
}