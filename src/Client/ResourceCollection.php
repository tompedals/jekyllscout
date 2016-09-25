<?php

namespace JekyllScout\Client;

use ArrayIterator;
use Countable;
use IteratorAggregate;

class ResourceCollection implements Countable, IteratorAggregate
{
    /**
     * @var int
     */
    private $page;

    /**
     * @var int
     */
    private $totalPages;

    /**
     * @var int
     */
    private $totalCount;

    /**
     * @var array
     */
    private $items = array();

    /**
     * @param int $page
     * @param int $totalPages
     * @param int $totalCount
     * @param array $items
     */
    public function __construct($page, $totalPages, $totalCount, array $items)
    {
        $this->page = $page;
        $this->totalPages = $totalPages;
        $this->totalCount = $totalCount;
        $this->items = $items;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function getNextPage()
    {
        if ($this->page === $this->totalPages) {
            return false;
        }

        return $this->page + 1;
    }

    /**
     * @return int
     */
    public function getPageCount()
    {
        return count($this->items);
    }

    /**
     * @return int
     */
    public function getTotalPages()
    {
        return $this->totalPages;
    }

    /**
     * @return int
     */
    public function getTotalCount()
    {
        return $this->totalCount;
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return $this->getPageCount();
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }
}
