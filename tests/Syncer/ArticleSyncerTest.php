<?php

namespace JekyllScout\Syncer;

use JekyllScout\Client\Model\Article;
use JekyllScout\Client\Model\ArticleRef;
use JekyllScout\Client\Model\Collection;
use JekyllScout\Repository\ArticleRepository;
use JekyllScout\Repository\CollectionRepository;
use JekyllScout\Writer\PageWriter;
use Mockery;

class ArticleSyncerTest extends \PHPUnit_Framework_TestCase
{
    private $articleRepository;
    private $collectionRepository;
    private $pageWriter;
    private $syncer;

    public function setUp()
    {
        $this->articleRepository = Mockery::mock(ArticleRepository::class);
        $this->collectionRepository = Mockery::mock(CollectionRepository::class);
        $this->pageWriter = Mockery::mock(PageWriter::class);
        $this->syncer = new ArticleSyncer($this->articleRepository, $this->collectionRepository, $this->pageWriter);
    }

    public function testCreatesPageForCategory()
    {
        $this->pageWriter->shouldReceive('prepare')
            ->with('/_helpscout_articles')
            ->once();

        $articleRef = new ArticleRef(array(
            'id' => '123',
            'number' => 123,
            'collectionId' => '456',
            'slug' => 'test',
            'name' => 'Test',
            'title' => 'Test',
            'categories' => array('987'),
            'related' => array('234', '345'),
            'createdAt' => '2016-09-01T00:00:00+00:00',
            'updatedAt' => '2016-09-01T00:00:00+00:00',
            'lastPublishedAt' => '2016-09-01T00:00:00+00:00',
        ));

        $this->articleRepository->shouldReceive('fetchAll')
            ->andReturn(array($articleRef));

        $collection = new Collection(array(
            'id' => '456',
            'number' => 123,
            'order' => 1,
            'name' => 'Test',
            'slug' => 'test',
            'visibility' => 'public',
            'createdAt' => '2016-09-01T00:00:00+00:00',
            'updatedAt' => '2016-09-01T00:00:00+00:00',
        ));

        $this->collectionRepository->shouldReceive('get')
            ->with('456')
            ->andReturn($collection);

        $article = new Article(array(
            'id' => '123',
            'number' => 123,
            'collectionId' => '456',
            'slug' => 'test',
            'name' => 'Test',
            'title' => 'Test',
            'text' => '<p>Content</p>',
            'categories' => array('987'),
            'related' => array('234', '345'),
            'createdAt' => '2016-09-01T00:00:00+00:00',
            'updatedAt' => '2016-09-01T00:00:00+00:00',
            'lastPublishedAt' => '2016-09-01T00:00:00+00:00',
        ));

        $this->articleRepository->shouldReceive('fetch')
            ->andReturn($article);

        $this->pageWriter->shouldReceive('write')
            ->with('/_helpscout_articles/123-test.html', '<p>Content</p>', array(
                'article_id' => '123',
                'number' => 123,
                'collection_id' => '456',
                'name' => 'Test',
                'title' => 'Test',
                'category_ids' => array('987'),
                'related_article_ids' => array('234', '345'),
                'created_at' => '2016-09-01T00:00:00+00:00',
                'updated_at' => '2016-09-01T00:00:00+00:00',
                'last_published_at' => '2016-09-01T00:00:00+00:00',
                'layout' => 'article',
            ))
            ->once();

        $this->syncer->sync('/_helpscout_articles', 'article');
    }

    public function testDoesNotCreatePageIfCollectionIsPrivate()
    {
    }
}
