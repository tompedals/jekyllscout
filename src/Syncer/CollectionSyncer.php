<?php

namespace JekyllScout\Syncer;

use JekyllScout\Client\Model\Collection;
use JekyllScout\Repository\CollectionRepository;
use JekyllScout\Writer\PageWriter;

class CollectionSyncer
{
    /**
     * @var CollectionRepository
     */
    private $collectionRepository;

    /**
     * @var PageWriter
     */
    private $pageWriter;

    /**
     * @param CollectionRepository $collectionRepository
     * @param PageWriter $pageWriter
     */
    public function __construct(
        CollectionRepository $collectionRepository,
        PageWriter $pageWriter
    ) {
        $this->collectionRepository = $collectionRepository;
        $this->pageWriter           = $pageWriter;
    }

    /**
     * @param string $collectionPath
     * @param string $siteId
     * @param string $layout
     */
    public function sync($collectionPath, $siteId, $layout)
    {
        $this->pageWriter->prepare($collectionPath);

        $collections = $this->collectionRepository->fetchAll($siteId);
        foreach ($collections as $collection) {
            if ($collection->isPublic()) {
                $frontmatter           = $this->getFrontmatter($collection);
                $frontmatter['layout'] = $layout;

                $this->pageWriter->write($collectionPath . $this->getPath($collection), '', $frontmatter);
            }
        }
    }

    /**
     * @param Collection $collection
     */
    private function getPath(Collection $collection)
    {
        return sprintf('/%d-%s.html', $collection->getNumber(), $collection->getSlug());
    }

    /**
     * @param Collection $collection
     */
    private function getFrontmatter(Collection $collection)
    {
        return [
            'collection_id'        => $collection->getId(),
            'number'               => $collection->getNumber(),
            'order'                => $collection->getOrder(),
            'name'                 => $collection->getName(),
            'title'                => $collection->getName(),
            'created_at'           => $collection->getCreatedAt()->format('c'),
            'updated_at'           => $collection->getUpdatedAt()->format('c'),
        ];
    }
}
