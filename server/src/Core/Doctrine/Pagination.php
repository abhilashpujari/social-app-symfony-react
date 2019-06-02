<?php

namespace App\Core\Doctrine;

use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Class Pagination
 * @package App\Core\Doctrine
 */
class Pagination
{
    /**
     * @var int
     */
    protected $currentPageNumber;
    /**
     * @var int
     */
    protected $itemsPerPage;
    /**
     * @var array
     */
    protected $pageRange;

    public function __construct($itemsPerPage, $currentPageNumber, $pageRange = 5)
    {
        $this->itemsPerPage = (int)$itemsPerPage;
        $this->pageRange = $pageRange;
        $this->currentPageNumber = ($currentPageNumber) ? (int)$currentPageNumber : 1;
    }

    /**
     * @param Query $query
     * @param $fetchJoinCollection
     */
    public function getPaginationData(Query $query, $fetchJoinCollection = true)
    {
        $query->setFirstResult((($this->currentPageNumber - 1) * $this->itemsPerPage))->setMaxResults($this->itemsPerPage);
        $items = new Paginator($query, $fetchJoinCollection);
    }
}