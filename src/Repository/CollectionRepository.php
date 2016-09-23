<?php

namespace JekyllScout\Repository;

use JekyllScout\Client\DocsClient;
use LogicException;

class CollectionRepository
{
    /**
     * @var Collection[]
     */
    private $items;

    /**
     * @var DocsClient
     */
    private $client;

    /**
     * @param DocsClient $client
     */
    public function __construct(DocsClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $siteId
     * @return Collection[]
     */
    public function fetchAll($siteId)
    {
        $this->items = [];

        $page = 1;
        while ($page !== false) {
            $collections = $this->client->listCollections($siteId, $page, 50);
            foreach ($collections as $collection) {
                $this->items[$collection->getId()] = $collection;
            }

            $page = $collections->getNextPage();
        }

        return $this->items;
    }

    /**
     * @return Collection[]
     */
    public function getAll()
    {
        $this->assertFetchCalled();

        return $this->items;
    }

    /**
     * @param string $id
     * @return Collection
     */
    public function get($id)
    {
        $this->assertFetchCalled();

        if (array_key_exists($id, $this->items)) {
            return $this->items[$id];
        }
    }

    /**
     * @throws LogicException
     */
    private function assertFetchCalled()
    {
        if ($this->items === null) {
            throw new LogicException('The collections have not been fetched.');
        }
    }
}
