<?php

namespace JekyllScout\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use JekyllScout\Client\Model\Article;
use JekyllScout\Client\Model\ArticleRef;
use JekyllScout\Client\Model\Category;
use JekyllScout\Client\Model\Collection;
use Psr\Http\Message\ResponseInterface;

class DocsClient
{
    const BASE_URL = 'https://docsapi.helpscout.net/v1';

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var Client
     */
    private $httpClient;

    /**
     * @param Client $httpClient
     */
    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param string $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @param string $collectionId
     * @param int $page
     * @param int $pageSize
     *
     * @return Category[]
     */
    public function listCategories($collectionId, $page = 1, $pageSize = 50)
    {
        $response = $this->get(self::BASE_URL.sprintf('/collections/%s/categories', $collectionId));

        $categories = array();
        foreach ($response['categories']['items'] as $data) {
            $categories[] = new Category($data);
        }

        return new ResourceCollection(
            $response['categories']['page'],
            $response['categories']['pages'],
            $response['categories']['count'],
            $categories
        );
    }

    /**
     * @param string $categoryId
     *
     * @return Category
     */
    public function getCategory($categoryId)
    {
        $response = $this->get(self::BASE_URL.sprintf('/categories/%s', $categoryId));

        return new Category($response['category']);
    }

    /**
     * @param int $page
     * @param int $pageSize
     *
     * @return Collection[]
     */
    public function listCollections($page = 1, $pageSize = 50)
    {
        $response = $this->get(self::BASE_URL.'/collections');

        $collections = array();
        foreach ($response['collections']['items'] as $data) {
            $collections[] = new Collection($data);
        }

        return new ResourceCollection(
            $response['collections']['page'],
            $response['collections']['pages'],
            $response['collections']['count'],
            $collections
        );
    }

    /**
     * @param string $collectionId
     *
     * @return Collection
     */
    public function getCollection($collectionId)
    {
        $response = $this->get(self::BASE_URL.sprintf('/collections/%s', $collectionId));

        return new Collection($response['collection']);
    }

    /**
     * @param string $collectionId
     * @param int    $page
     * @param int    $pageSize
     *
     * @return ArticleRef[]
     */
    public function listArticles($collectionId, $page = 1, $pageSize = 50)
    {
        $response = $this->get(self::BASE_URL.sprintf('/collections/%s/articles', $collectionId), array(
            'query' => array(
                'page' => $page,
                'pageSize' => $pageSize,
                'status' => 'published',
            ),
        ));

        $articleRefs = array();
        foreach ($response['articles']['items'] as $data) {
            $articleRefs[] = new ArticleRef($data);
        }

        return new ResourceCollection(
            $response['articles']['page'],
            $response['articles']['pages'],
            $response['articles']['count'],
            $articleRefs
        );
    }

    /**
     * @param string $articleId
     *
     * @return ArticleRef
     */
    public function getArticle($articleId)
    {
        $response = $this->get(self::BASE_URL.sprintf('/articles/%s', $articleId));

        return new Article($response['article']);
    }

    /**
     * @param string $url
     * @param array  $options
     *
     * @return array
     */
    private function get($url, array $options = array())
    {
        return $this->getResponseContent($this->rawRequest('GET', $url, $options));
    }

    /**
     * @param string $method
     * @param string $url
     * @param array  $options
     *
     * @return ResponseInterface
     */
    private function rawRequest($method, $url, array $options = array())
    {
        // Authenticate all requests with the API key and a dummy password
        $options['auth'] = array($this->apiKey, 'X');

        return $this->httpClient->request($method, $url, $options);
    }

    /**
     * @param ResponseInterface $rawResponse
     *
     * @return array The JSON decoded data from the response
     */
    private function getResponseContent(ResponseInterface $rawResponse)
    {
        $content = $rawResponse->getBody();
        if (!$content) {
            return array();
        }

        return json_decode($content, true);
    }
}
