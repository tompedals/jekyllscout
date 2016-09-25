<?php

namespace JekyllScout\Config;

class Config
{
    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $siteId;

    /**
     * @var string
     */
    private $articleCollection = 'helpscout_articles';

    /**
     * @var string
     */
    private $articleLayout = 'article';

    /**
     * @var string
     */
    private $categoryCollection = 'helpscout_categories';

    /**
     * @var string
     */
    private $categoryLayout = 'category';

    /**
     * @var string
     */
    private $collectionCollection = 'helpscout_collections';

    /**
     * @var string
     */
    private $collectionLayout = 'collection';

    public function __construct(array $options)
    {
        if (isset($options['api_key'])) {
            $this->apiKey = $options['api_key'];
        }

        if (isset($options['site_id'])) {
            $this->siteId = $options['site_id'];
        }

        if (isset($options['article_collection'])) {
            $this->articleCollection = $options['article_collection'];
        }

        if (isset($options['article_layout'])) {
            $this->articleLayout = $options['article_layout'];
        }

        if (isset($options['category_collection'])) {
            $this->categoryCollection = $options['category_collection'];
        }

        if (isset($options['category_layout'])) {
            $this->categoryLayout = $options['category_layout'];
        }

        if (isset($options['collection_collection'])) {
            $this->collectionCollection = $options['collection_collection'];
        }

        if (isset($options['collection_layout'])) {
            $this->collectionLayout = $options['collection_layout'];
        }
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @return string
     */
    public function getSiteId()
    {
        return $this->siteId;
    }

    /**
     * @return string
     */
    public function getArticleCollection()
    {
        return $this->articleCollection;
    }

    /**
     * @return string
     */
    public function getArticleCollectionPath($rootPath)
    {
        return $rootPath.'/_'.$this->articleCollection;
    }

    /**
     * @return string
     */
    public function getArticleLayout()
    {
        return $this->articleLayout;
    }

    /**
     * @return string
     */
    public function getCategoryCollection()
    {
        return $this->categoryCollection;
    }

    /**
     * @return string
     */
    public function getCategoryCollectionPath($rootPath)
    {
        return $rootPath.'/_'.$this->categoryCollection;
    }

    /**
     * @return string
     */
    public function getCategoryLayout()
    {
        return $this->categoryLayout;
    }

    /**
     * @return string
     */
    public function getCollectionCollection()
    {
        return $this->collectionCollection;
    }

    /**
     * @return string
     */
    public function getCollectionCollectionPath($rootPath)
    {
        return $rootPath.'/_'.$this->collectionCollection;
    }

    /**
     * @return string
     */
    public function getCollectionLayout()
    {
        return $this->collectionLayout;
    }
}
