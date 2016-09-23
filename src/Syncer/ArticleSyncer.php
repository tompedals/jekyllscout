<?php

namespace JekyllScout\Syncer;

use JekyllScout\Client\Model\Article;
use JekyllScout\Repository\ArticleRepository;
use JekyllScout\Repository\CategoryRepository;
use JekyllScout\Repository\CollectionRepository;
use JekyllScout\Writer\PageWriter;

class ArticleSyncer
{
    /**
     * @var ArticleRepository
     */
    private $articleRepository;

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
     * @param ArticleRepository $articleRepository
     * @param CollectionRepository $collectionRepository
     * @param CategoryRepository $categoryRepository
     * @param PageWriter $pageWriter
     */
    public function __construct(
        ArticleRepository $articleRepository,
        CollectionRepository $collectionRepository,
        CategoryRepository $categoryRepository,
        PageWriter $pageWriter
    ) {
        $this->articleRepository    = $articleRepository;
        $this->categoryRepository   = $categoryRepository;
        $this->collectionRepository = $collectionRepository;
        $this->pageWriter           = $pageWriter;
    }

    /**
     * @param string $collectionPath
     * @param string $layout
     */
    public function sync($collectionPath, $layout)
    {
        $this->pageWriter->prepare($collectionPath);

        $articleRefs = $this->articleRepository->fetchAll();
        foreach ($articleRefs as $articleRef) {
            $collection = $this->collectionRepository->get($articleRef->getCollectionId());
            if ($collection->isPublic()) {
                $article               = $this->articleRepository->fetch($articleRef->getId());
                $frontmatter           = $this->getFrontmatter($article);
                $frontmatter['layout'] = $layout;

                $this->pageWriter->write(
                    $collectionPath . $this->getPath($article),
                    $article->getText(),
                    $frontmatter
                );
            }
        }
    }

    /**
     * @param Article $article
     */
    private function getPath(Article $article)
    {
        return sprintf('/%d-%s.html', $article->getNumber(), $article->getSlug());
    }

    /**
     * @param Article $article
     */
    private function getFrontmatter(Article $article)
    {
        return [
            'article_id'                => $article->getId(),
            'number'                    => $article->getNumber(),
            'collection_id'             => $article->getCollectionId(),
            'name'                      => $article->getName(),
            'title'                     => $article->getName(),
            'category_ids'              => $article->getCategoryIds(),
            'related_article_ids'       => $article->getRelatedArticleIds(),
            'created_at'                => $article->getCreatedAt()->format('c'),
            'updated_at'                => $article->getUpdatedAt()->format('c'),
            'last_published_at'         => $article->getLastPublishedAt()->format('c'),
        ];
    }
}
