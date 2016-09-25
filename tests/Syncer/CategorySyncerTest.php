<?php

namespace JekyllScout\Syncer;

use JekyllScout\Client\Model\Category;
use JekyllScout\Client\Model\Collection;
use JekyllScout\Repository\CategoryRepository;
use JekyllScout\Repository\CollectionRepository;
use JekyllScout\Writer\PageWriter;
use Mockery;

class CategorySyncerTest extends \PHPUnit_Framework_TestCase
{
    private $categoryRepository;
    private $collectionRepository;
    private $pageWriter;
    private $syncer;

    public function setUp()
    {
        $this->categoryRepository = Mockery::mock(CategoryRepository::class);
        $this->collectionRepository = Mockery::mock(CollectionRepository::class);
        $this->pageWriter = Mockery::mock(PageWriter::class);
        $this->syncer = new CategorySyncer($this->categoryRepository, $this->collectionRepository, $this->pageWriter);
    }

    public function testCreatesPageForCategory()
    {
        $this->pageWriter->shouldReceive('prepare')
            ->with('/_helpscout_categories')
            ->once();

        $category = new Category(array(
            'id' => '123',
            'number' => 123,
            'collectionId' => '456',
            'order' => 1,
            'name' => 'Test',
            'slug' => 'test',
            'visibility' => 'public',
            'articleCount' => 5,
            'createdAt' => '2016-09-01T00:00:00+00:00',
            'updatedAt' => '2016-09-01T00:00:00+00:00',
        ));

        $this->categoryRepository->shouldReceive('fetchAll')
            ->andReturn(array($category));

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

        $this->pageWriter->shouldReceive('write')
            ->with('/_helpscout_categories/123-test.html', '', array(
                'category_id' => '123',
                'number' => 123,
                'collection_id' => '456',
                'order' => 1,
                'name' => 'Test',
                'title' => 'Test',
                'article_count' => 5,
                'created_at' => '2016-09-01T00:00:00+00:00',
                'updated_at' => '2016-09-01T00:00:00+00:00',
                'layout' => 'category',
            ))
            ->once();

        $this->syncer->sync('/_helpscout_categories', 'category');
    }

    public function testDoesNotCreatePageIfCollectionIsPrivate()
    {
        $this->pageWriter->shouldReceive('prepare')
            ->with('/_helpscout_categories')
            ->once();

        $category = new Category(array(
            'id' => '123',
            'number' => 123,
            'collectionId' => '456',
            'order' => 1,
            'name' => 'Test',
            'slug' => 'test',
            'visibility' => 'public',
            'articleCount' => 5,
            'createdAt' => '2016-09-01T00:00:00+00:00',
            'updatedAt' => '2016-09-01T00:00:00+00:00',
        ));

        $this->categoryRepository->shouldReceive('fetchAll')
            ->andReturn(array($category));

        $collection = new Collection(array(
            'id' => '456',
            'number' => 123,
            'order' => 1,
            'name' => 'Test',
            'slug' => 'test',
            'visibility' => 'private',
            'createdAt' => '2016-09-01T00:00:00+00:00',
            'updatedAt' => '2016-09-01T00:00:00+00:00',
        ));

        $this->collectionRepository->shouldReceive('get')
            ->with('456')
            ->andReturn($collection);

        $this->pageWriter->shouldReceive('write')
            ->never();

        $this->syncer->sync('/_helpscout_categories', 'category');
    }
}
