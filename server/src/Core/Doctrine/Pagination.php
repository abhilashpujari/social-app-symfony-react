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
     * @param bool|true $fetchJoinCollection
     * @return array
     */
    public function getPaginationData(Query $query, $fetchJoinCollection = true)
    {
        $paginator = new Paginator($query, $fetchJoinCollection);
        $pages = ceil(count($paginator) / $this->itemsPerPage);
        $offset = ($this->currentPageNumber - 1) * $this->itemsPerPage;
        $query->setFirstResult($offset)->setMaxResults($this->itemsPerPage);

        return [
            'result' => $paginator, 'currentPage' => $this->currentPageNumber, 'pages' => $pages,
            'count' => count($paginator), 'offset' => $offset, 'limitPerPage' => $this->itemsPerPage
        ];
    }
}