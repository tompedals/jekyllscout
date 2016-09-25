<?php

namespace JekyllScout\Client\Model;

use DateTime;

/**
 * A representation of the Help Scout Docs API Category response.
 */
class Category
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var int
     */
    private $number;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var string
     */
    private $collectionId;

    /**
     * @var int
     */
    private $order;

    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $articleCount;

    /**
     * @var DateTime
     */
    private $createdAt;

    /**
     * @var DateTime
     */
    private $updatedAt;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->id = isset($data['id']) ? $data['id'] : null;
        $this->number = isset($data['number']) ? $data['number'] : null;
        $this->slug = isset($data['slug']) ? $data['slug'] : null;
        $this->collectionId = isset($data['collectionId']) ? $data['collectionId'] : null;
        $this->order = isset($data['order']) ? $data['order'] : null;
        $this->name = isset($data['name']) ? $data['name'] : null;
        $this->articleCount = isset($data['articleCount']) ? $data['articleCount'] : null;
        $this->createdAt = isset($data['createdAt']) ? new DateTime($data['createdAt']) : null;
        $this->updatedAt = isset($data['updatedAt']) ? new DateTime($data['updatedAt']) : null;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @return string
     */
    public function getCollectionId()
    {
        return $this->collectionId;
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getArticleCount()
    {
        return $this->articleCount;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}
