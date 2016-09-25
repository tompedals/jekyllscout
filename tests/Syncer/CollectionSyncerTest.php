<?php

namespace JekyllScout\Syncer;

use JekyllScout\Client\Model\Collection;
use JekyllScout\Repository\CollectionRepository;
use JekyllScout\Writer\PageWriter;
use Mockery;

class CollectionSyncerTest extends \PHPUnit_Framework_TestCase
{
    private $collectionRepository;
    private $pageWriter;
    private $syncer;

    public function setUp()
    {
        $this->collectionRepository = Mockery::mock(CollectionRepository::class);
        $this->pageWriter = Mockery::mock(PageWriter::class);
        $this->syncer = new CollectionSyncer($this->collectionRepository, $this->pageWriter);
    }

    public function testCreatesPageForCollection()
    {
        $this->pageWriter->shouldReceive('prepare')
            ->with('/_helpscout_collections')
            ->once();

        $collection = new Collection(array(
            'id' => '123',
            'number' => 123,
            'order' => 1,
            'name' => 'Test',
            'slug' => 'test',
            'visibility' => 'public',
            'createdAt' => '2016-09-01T00:00:00+00:00',
            'updatedAt' => '2016-09-01T00:00:00+00:00',
        ));

        $this->collectionRepository->shouldReceive('fetchAll')
            ->with('123')
            ->andReturn(array($collection));

        $this->pageWriter->shouldReceive('write')
            ->with('/_helpscout_collections/123-test.html', '', array(
                'collection_id' => '123',
                'number' => 123,
                'order' => 1,
                'name' => 'Test',
                'title' => 'Test',
                'created_at' => '2016-09-01T00:00:00+00:00',
                'updated_at' => '2016-09-01T00:00:00+00:00',
                'layout' => 'collection',
            ))
            ->once();

        $this->syncer->sync('/_helpscout_collections', '123', 'collection');
    }

    public function testDoesNotCreatePageForPrivateCollection()
    {
        $this->pageWriter->shouldReceive('prepare')
            ->with('/_helpscout_collections')
            ->once();

        $collection = new Collection(array(
            'id' => '123',
            'number' => 123,
            'order' => 1,
            'name' => 'Test',
            'slug' => 'test',
            'visibility' => 'private',
            'createdAt' => '2016-09-01T00:00:00+00:00',
            'updatedAt' => '2016-09-01T00:00:00+00:00',
        ));

        $this->collectionRepository->shouldReceive('fetchAll')
            ->with('123')
            ->andReturn(array($collection));

        $this->pageWriter->shouldReceive('write')
            ->never();

        $this->syncer->sync('/_helpscout_collections', '123', 'collection');
    }
}
