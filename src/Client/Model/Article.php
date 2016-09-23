<?php

namespace JekyllScout\Client\Model;

/**
 * A representation of the Help Scout Docs API Article response.
 */
class Article extends ArticleRef
{
    /**
     * @var string
     */
    private $text;

    /**
     * @var array
     */
    private $categoryIds;

    /**
     * @var array
     */
    private $relatedArticleIds;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->text              = isset($data['text']) ? $data['text'] : null;
        $this->categoryIds       = isset($data['categories']) ? $data['categories'] : [];
        $this->relatedArticleIds = isset($data['related']) ? $data['related'] : [];
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return array
     */
    public function getCategoryIds()
    {
        return $this->categoryIds;
    }

    /**
     * @return array
     */
    public function getRelatedArticleIds()
    {
        return $this->relatedArticleIds;
    }
}
