<?php

namespace JekyllScout\Client\Model;

use DateTime;

/**
 * A representation of the Help Scout Docs API Collection response.
 */
class Collection
{
    const VISIBILITY_PUBLIC  = 'public';
    const VISIBILITY_PRIVATE = 'private';

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
    private $siteId;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var string
     */
    private $visibility;

    /**
     * @var int
     */
    private $order;

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
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->id         = isset($data['id']) ? $data['id'] : null;
        $this->number     = isset($data['number']) ? $data['number'] : null;
        $this->siteId     = isset($data['siteId']) ? $data['siteId'] : null;
        $this->slug       = isset($data['slug']) ? $data['slug'] : null;
        $this->visibility = isset($data['visibility']) ? $data['visibility'] : null;
        $this->order      = isset($data['order']) ? $data['order'] : null;
        $this->name       = isset($data['name']) ? $data['name'] : null;
        $this->createdAt  = isset($data['createdAt']) ? new DateTime($data['createdAt']) : null;
        $this->updatedAt  = isset($data['updatedAt']) ? new DateTime($data['updatedAt']) : null;
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
     * @var string
     */
    public function getSiteId()
    {
        return $this->siteId;
    }

    /**
     * @var string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @var string
     */
    public function getVisibility()
    {
        return $this->visibility;
    }

    /**
     * @return bool
     */
    public function isPublic()
    {
        return $this->getVisibility() === self::VISIBILITY_PUBLIC;
    }

    /**
     * @return bool
     */
    public function isPrivate()
    {
        return $this->getVisibility() === self::VISIBILITY_PRIVATE;
    }

    /**
     * @var int
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
     * @var DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @var DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}
