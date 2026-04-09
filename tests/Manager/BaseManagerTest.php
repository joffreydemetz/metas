<?php

namespace JDZ\Metas\Tests\Manager;

use PHPUnit\Framework\TestCase;
use JDZ\Metas\Metas;
use JDZ\Metas\Manager\BaseManager;

class BaseManagerTest extends TestCase
{
    private Metas $metas;
    private BaseManager $base;

    protected function setUp(): void
    {
        $this->metas = new Metas();
        $this->base = new BaseManager();
        $this->metas->register($this->base);
    }

    public function testSetBase(): void
    {
        $this->base->setBase('https://example.com/');

        $elements = $this->metas->getElements();
        $this->assertEquals('base', $elements[0]['tag']);
        $this->assertEquals('https://example.com/', $elements[0]['attrs']['href']);
    }

    public function testSetCharset(): void
    {
        $this->base->setCharset('utf-8');

        $elements = $this->metas->getElements();
        $this->assertEquals('utf-8', $elements[0]['attrs']['charset']);
    }

    public function testSetXUaCompatible(): void
    {
        $this->base->setXUaCompatible('IE=edge');

        $elements = $this->metas->getElements();
        $this->assertEquals('X-UA-Compatible', $elements[0]['attrs']['http-equiv']);
        $this->assertEquals('IE=edge', $elements[0]['attrs']['content']);
    }

    public function testSetCacheControl(): void
    {
        $this->base->setCacheControl('no-cache');

        $elements = $this->metas->getElements();
        $this->assertEquals('cache-control', $elements[0]['attrs']['http-equiv']);
    }

    public function testSetApplicationName(): void
    {
        $this->base->setApplicationName('My App');

        $elements = $this->metas->getElements();
        $this->assertEquals('application-name', $elements[0]['attrs']['name']);
        $this->assertEquals('My App', $elements[0]['attrs']['content']);
    }

    public function testSetPrefetch(): void
    {
        $this->base->setPrefetch('https://cdn.example.com');

        $elements = $this->metas->getElements();
        $this->assertEquals('link', $elements[0]['tag']);
        $this->assertEquals('prefetch', $elements[0]['attrs']['rel']);
    }

    public function testSetPreconnect(): void
    {
        $this->base->setPreconnect('https://fonts.googleapis.com');

        $elements = $this->metas->getElements();
        $this->assertEquals('preconnect', $elements[0]['attrs']['rel']);
    }

    public function testSetGenerator(): void
    {
        $this->base->setGenerator('JDZ CMS');

        $elements = $this->metas->getElements();
        $this->assertEquals('generator', $elements[0]['attrs']['name']);
        $this->assertEquals('JDZ CMS', $elements[0]['attrs']['content']);
    }

    public function testFluentInterface(): void
    {
        $this->assertSame($this->base, $this->base->setTitle('T'));
        $this->assertSame($this->base, $this->base->setDescription('D'));
        $this->assertSame($this->base, $this->base->setKeywords('K'));
        $this->assertSame($this->base, $this->base->setViewport('V'));
        $this->assertSame($this->base, $this->base->setRobots('R'));
        $this->assertSame($this->base, $this->base->setAuthor('A'));
        $this->assertSame($this->base, $this->base->setUrl('U'));
    }
}
