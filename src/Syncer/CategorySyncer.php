<?php

namespace JekyllScout\Syncer;

use JekyllScout\Client\Model\Category;
use JekyllScout\Repository\CategoryRepository;
use JekyllScout\Repository\CollectionRepository;
use JekyllScout\Writer\PageWriter;

class CategorySyncer
{
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * @var CollectionRepository
     */
    private $collectionRepository;

    /**
     * @var PageWriter
     */
    private $pageWriter;

    /**
     * @param CategoryRepository $categoryRepository
     * @param CollectionRepository $collectionRepository
     * @param PageWriter $pageWriter
     */
    public function __construct(
        CategoryRepository $categoryRepository,
        CollectionRepository $collectionRepository,
        PageWriter $pageWriter
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->collectionRepository = $collectionRepository;
        $this->pageWriter = $pageWriter;
    }

    /**
     * @param string $collectionPath
     * @param string $layout
     */
    public function sync($collectionPath, $layout)
    {
        $this->pageWriter->prepare($collectionPath);

        $categories = $this->categoryRepository->fetchAll();
        foreach ($categories as $category) {
            $collection = $this->collectionRepository->get($category->getCollectionId());
            if ($collection->isPublic()) {
                $frontmatter = $this->getFrontmatter($category);
                $frontmatter['layout'] = $layout;

                $this->pageWriter->write($collectionPath.$this->getPath($category), '', $frontmatter);
            }
        }
    }

    /**
     * @param Category $category
     */
    private function getPath(Category $category)
    {
        return sprintf('/%d-%s.html', $category->getNumber(), $category->getSlug());
    }

    /**
     * @param Category $category
     */
    private function getFrontmatter(Category $category)
    {
        return array(
            'category_id' => $category->getId(),
            'number' => $category->getNumber(),
            'collection_id' => $category->getCollectionId(),
            'order' => $category->getOrder(),
            'name' => $category->getName(),
            'title' => $category->getName(),
            'article_count' => $category->getArticleCount(),
            'created_at' => $category->getCreatedAt()->format('c'),
            'updated_at' => $category->getUpdatedAt()->format('c'),
        );
    }
}
