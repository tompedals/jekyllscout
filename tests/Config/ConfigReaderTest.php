<?php

namespace JekyllScout\Config;

use InvalidArgumentException;
use org\bovigo\vfs\vfsStream;

class ConfigReaderTest extends \PHPUnit_Framework_TestCase
{
    private $configReader;

    public function setUp()
    {
        $this->configReader = new ConfigReader();
    }

    public function testThrowsExceptionWhenConfigFileDoesNotExist()
    {
        $this->expectException(InvalidArgumentException::class);

        vfsStream::setup('root', null, []);

        $this->configReader->read(vfsStream::url('root'));
    }

    public function testReadsConfig()
    {
        $this->expectException(InvalidArgumentException::class);

        $yaml = <<<'YAML'
api_key: secret
site_id: a1b2c3
article_collection: articles
article_layout: article
category_collection: categories
category_layout: category
collection_collection: collections
collection_layout: collection
YAML;

        vfsStream::setup('root', null, [
            '.jekyllscout' => $yaml,
        ]);

        $config = $this->configReader->read(vfsStream::url('root'));

        $this->assertInstanceOf(Config::class, $config);
        $this->assertSame('secret', $config->getApiKey());
        $this->assertSame('a1b2c3', $config->getSiteId());
        $this->assertSame('articles', $config->getArticleCollection());
        $this->assertSame('article', $config->getArticleLayout());
        $this->assertSame('categories', $config->getCategoryCollection());
        $this->assertSame('category', $config->getCategoryLayout());
        $this->assertSame('collections', $config->getCollectionCollection());
        $this->assertSame('collection', $config->getCollectionLayout());
    }
}
