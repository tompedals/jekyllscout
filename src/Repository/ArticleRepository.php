<?php

namespace JekyllScout\Repository;

use JekyllScout\Client\DocsClient;
use LogicException;

class ArticleRepository
{
    /**
     * @var ArticleRef[]
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
        $this->client = $client;
        $this->collectionRepository = $collectionRepository;
    }

    /**
     * @return ArticleRef[]
     */
    public function fetchAll()
    {
        $this->items = array();

        foreach ($this->collectionRepository->getAll() as $collection) {
            $page = 1;
            while ($page !== false) {
                $articles = $this->client->listArticles($collection->getId(), $page, 50);
                foreach ($articles as $article) {
                    $this->items[$article->getId()] = $article;
                }

                $page = $articles->getNextPage();
            }
        }

        return $this->items;
    }

    /**
     * @return ArticleRef[]
     */
    public function getAll()
    {
        $this->assertFetchCalled();

        return $this->items;
    }

    /**
     * @param string $id
     *
     * @return Article
     */
    public function fetch($id)
    {
        return $this->client->getArticle($id);
    }

    /**
     * @throws LogicException
     */
    private function assertFetchCalled()
    {
        if ($this->items === null) {
            throw new LogicException('The articles have not been fetched.');
        }
    }
}
