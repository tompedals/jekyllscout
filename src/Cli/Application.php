<?php

namespace JekyllScout\Cli;

use GuzzleHttp\Client;
use JekyllScout\Cli\Command\SyncCommand;
use JekyllScout\Client\DocsClient;
use JekyllScout\Config\ConfigReader;
use JekyllScout\Repository\ArticleRepository;
use JekyllScout\Repository\CategoryRepository;
use JekyllScout\Repository\CollectionRepository;
use JekyllScout\Syncer\ArticleSyncer;
use JekyllScout\Syncer\CategorySyncer;
use JekyllScout\Syncer\CollectionSyncer;
use JekyllScout\Writer\PageWriter;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Filesystem\Filesystem;

class Application extends BaseApplication
{
    const VERSION = '1.0';

    public function __construct()
    {
        parent::__construct('jekyllscout', self::VERSION);

        $docsClient           = new DocsClient(new Client());
        $collectionRepository = new CollectionRepository($docsClient);
        $categoryRepository   = new CategoryRepository($docsClient, $collectionRepository);
        $articleRepository    = new ArticleRepository($docsClient, $collectionRepository);
        $pageWriter           = new PageWriter(new Filesystem());

        $this->add(new SyncCommand(
            'sync',
            $docsClient,
            new ConfigReader(),
            new CollectionSyncer($collectionRepository, $pageWriter),
            new CategorySyncer($categoryRepository, $collectionRepository, $pageWriter),
            new ArticleSyncer($articleRepository, $collectionRepository, $categoryRepository, $pageWriter)
        ));
    }
}
