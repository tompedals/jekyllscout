<?php

namespace JekyllScout\Repository;

use JekyllScout\Client\DocsClient;
use LogicException;

class CategoryRepository
{
    /**
     * @var Category[]
     */
    private $items;

    /**
     * @var DocsClient
     */
    private $client;

    /**
     * @param DocsClient $client
     * @param CollectionRepository $collectionRepository
     */
    public function __construct(DocsClient $client, CollectionRepository $collectionRepository)
    {
        $this->client               = $client;
        $this->collectionRepository = $collectionRepository;
    }

    /**
     * @return Category[]
     */
    public function fetchAll()
    {
        $this->items = [];

        foreach ($this->collectionRepository->getAll() as $collection) {
            $page = 1;
            while ($page !== false) {
                $categories = $this->client->listCategories($collection->getId(), $page, 50);
                foreach ($categories as $category) {
                    $this->items[$category->getId()] = $category;
                }

                $page = $categories->getNextPage();
            }
        }

        return $this->items;
    }

    /**
     * @return Category[]
     */
    public function getAll()
    {
        $this->assertFetchCalled();

        return $this->items;
    }

    /**
     * @param string $id
     * @return Category
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
            throw new LogicException('The categories have not been fetched.');
        }
    }
}
