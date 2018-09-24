<?php
namespace StudentList\Helpers;

class Pager
{
    /**
     * @var int
     */
    protected $perPage = 10;

    /**
     * @var int
     */
    protected $paginationLinks = 5;

    /**
     * Calculates offset for DB query based on the current page
     *
     * @param int $page
     *
     * @return int
     */
    public function calculatePositioning(int $page): int
    {
        return $page > 1 ? ($page * $this->perPage) - $this->perPage : 0;
    }

    /**
     * Calculates how many pages are needed to display $records
     *
     * @param int $records
     *
     * @return float
     */
    public function calculateTotalPages(int $records): float
    {
        return ceil($records/$this->perPage);
    }

    /**
     * Calculates starting link from which pagination links will be printed out
     *
     * @param int $page
     *
     * @return int
     */
    public function calculateStartingPoint(int $page): int
    {
        return ($page - $this->paginationLinks > 0) ? $page - $this->paginationLinks : 1;
    }

    /**
     * Calculates the last link which will be printed out
     *
     * @param int $page
     * @param int $totalPages
     *
     * @return int
     */
    public function calculateEndingPoint(int $page, int $totalPages): int
    {
        return ($page + $this->paginationLinks < $totalPages) ? $page + $this->paginationLinks : $totalPages;
    }

    /**
     * @return int
     */
    public function getPerPage(): int
    {
        return $this->perPage;
    }

    /**
     * @param int $perPage
     */
    public function setPerPage(int $perPage)
    {
        $this->perPage = $perPage;
    }

    /**
     * @return int
     */
    public function getPaginationLinks(): int
    {
        return $this->paginationLinks;
    }

    /**
     * @param int $paginationLinks
     */
    public function setPaginationLinks(int $paginationLinks)
    {
        $this->paginationLinks = $paginationLinks;
    }
}