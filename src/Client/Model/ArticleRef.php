<?php

namespace JekyllScout\Client\Model;

use DateTime;

/**
 * A representation of the Help Scout Docs API ArticleRef response.
 */
class ArticleRef
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
    private $collectionId;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var string
     */
    private $name;

    /**
     * @var DateTime
     */
    private $createdAt;

    /**
     * @var DateTime
     */
    private $updatedAt;

    /**
     * @var DateTime
     */
    private $lastPublishedAt;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->id                 = isset($data['id']) ? $data['id'] : null;
        $this->number             = isset($data['number']) ? $data['number'] : null;
        $this->collectionId       = isset($data['collectionId']) ? $data['collectionId'] : null;
        $this->slug               = isset($data['slug']) ? $data['slug'] : null;
        $this->name               = isset($data['name']) ? $data['name'] : null;
        $this->createdAt          = isset($data['createdAt']) ? new DateTime($data['createdAt']) : null;
        $this->updatedAt          = isset($data['updatedAt']) ? new DateTime($data['updatedAt']) : null;
        $this->lastPublishedAt    = isset($data['lastPublishedAt']) ? new DateTime($data['lastPublishedAt']) : null;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @var int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @return string
     */
    public function getCollectionId()
    {
        return $this->collectionId;
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
    public function getName()
    {
        return $this->name;
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

    /**
     * @return DateTime
     */
    public function getLastPublishedAt()
    {
        return $this->lastPublishedAt;
    }
}
