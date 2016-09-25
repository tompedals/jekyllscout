<?php

namespace JekyllScout\Cli\Command;

use InvalidArgumentException;
use JekyllScout\Client\DocsClient;
use JekyllScout\Config\ConfigReader;
use JekyllScout\Syncer\ArticleSyncer;
use JekyllScout\Syncer\CategorySyncer;
use JekyllScout\Syncer\CollectionSyncer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SyncCommand extends Command
{
    /**
     * @var DocsClient
     */
    private $docsClient;

    /**
     * @var ConfigReader
     */
    private $configReader;

    /**
     * @var CollectionSyncer
     */
    private $collectionSyncer;

    /**
     * @var CategorySyncer
     */
    private $categorySyncer;

    /**
     * @var ArticleSyncer
     */
    private $articleSyncer;

    /**
     * @param string $name
     * @param DocsClient $docsClient
     * @param ConfigReader $configReader
     * @param CollectionSyncer $collectionSyncer
     * @param CategorySyncer $categorySyncer
     * @param ArticleSyncer $articleSyncer
     */
    public function __construct(
        $name = null,
        DocsClient $docsClient,
        ConfigReader $configReader,
        CollectionSyncer $collectionSyncer,
        CategorySyncer $categorySyncer,
        ArticleSyncer $articleSyncer
    ) {
        parent::__construct($name);

        $this->docsClient = $docsClient;
        $this->configReader = $configReader;
        $this->collectionSyncer = $collectionSyncer;
        $this->categorySyncer = $categorySyncer;
        $this->articleSyncer = $articleSyncer;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->addArgument('path', InputArgument::REQUIRED, 'The path to the Jekyll site');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = trim($input->getArgument('path'));
        if (!is_dir($path)) {
            throw new InvalidArgumentException(sprintf('The path "%s" does not exist', $path));
        }

        $config = $this->configReader->read($path);

        // Set the API key from the config
        $this->docsClient->setApiKey($config->getApiKey());

        $output->writeln('Syncing content from Help Scout');

        // NB: The collections must be synced first as they are used to fetch the categories and articles
        $output->writeln('Syncing collections...');
        $this->collectionSyncer->sync(
            $config->getCollectionCollectionPath($path),
            $config->getSiteId(),
            $config->getCollectionLayout()
        );

        $output->writeln('Syncing categories...');
        $this->categorySyncer->sync(
            $config->getCategoryCollectionPath($path),
            $config->getCategoryLayout()
        );

        $output->writeln('Syncing articles...');
        $this->articleSyncer->sync(
            $config->getArticleCollectionPath($path),
            $config->getArticleLayout()
        );
    }
}
