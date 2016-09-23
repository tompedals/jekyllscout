<?php

namespace JekyllScout\Cli\Command;

use InvalidArgumentException;
use JekyllScout\Client\DocsClient;
use JekyllScout\Config\Config;
use JekyllScout\Config\ConfigReader;
use JekyllScout\Syncer\ArticleSyncer;
use JekyllScout\Syncer\CategorySyncer;
use JekyllScout\Syncer\CollectionSyncer;
use Mockery;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class CreateTasksCommandTest extends \PHPUnit_Framework_TestCase
{
    private $docsClient;
    private $configReader;
    private $collectionSyncer;
    private $categorySyncer;
    private $articleSyncer;
    private $command;

    public function setUp()
    {
        $this->docsClient       = Mockery::mock(DocsClient::class);
        $this->configReader     = Mockery::mock(ConfigReader::class);
        $this->collectionSyncer = Mockery::mock(CollectionSyncer::class);
        $this->categorySyncer   = Mockery::mock(CategorySyncer::class);
        $this->articleSyncer    = Mockery::mock(ArticleSyncer::class);

        $this->command = new SyncCommand(
            'test',
            $this->docsClient,
            $this->configReader,
            $this->collectionSyncer,
            $this->categorySyncer,
            $this->articleSyncer
        );
    }

    public function testThrowsExceptionWhenPathNotFound()
    {
        $this->expectException(InvalidArgumentException::class);

        $commandTester = new CommandTester($this->command);
        $commandTester->execute([
            'path' => __DIR__ . '/doesnotexist',
        ]);
    }

    public function testSyncs()
    {
        $config = new Config([
            'api_key' => 'secret',
            'site_id' => '123',
        ]);
        $this->configReader->shouldReceive('read')
            ->with(__DIR__)
            ->andReturn($config)
            ->once();

        $this->docsClient->shouldReceive('setApiKey')
            ->with('secret')
            ->once();

        $this->collectionSyncer->shouldReceive('sync')
            ->with(__DIR__ . '/_collections', '123')
            ->once();

        $this->categorySyncer->shouldReceive('sync')
            ->with(__DIR__ . '/_categories')
            ->once();

        $this->articleSyncer->shouldReceive('sync')
            ->with(__DIR__ . '/_articles')
            ->once();

        $commandTester = new CommandTester($this->command);
        $commandTester->execute([
            'path' => __DIR__,
        ]);
    }
}
