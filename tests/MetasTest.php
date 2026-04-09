<?php

namespace JDZ\Metas\Tests;

use PHPUnit\Framework\TestCase;
use JDZ\Metas\Metas;
use JDZ\Metas\Manager\BaseManager;
use JDZ\Metas\Manager\OgManager;
use JDZ\Metas\Manager\TwitterManager;

class MetasTest extends TestCase
{
    private function createMetas(): Metas
    {
        $metas = new Metas();
        $metas->register(new BaseManager());
        return $metas;
    }

    // ========================================
    // CORE FUNCTIONALITY
    // ========================================

    public function testConstructorDefaults(): void
    {
        $metas = new Metas();
        $this->assertEmpty($metas->getElements());
    }

    public function testCustomConfig(): void
    {
        $metas = new Metas(['title_limit' => 50]);
        $metas->register(new BaseManager());

        $metas->set('title', str_repeat('A', 100));

        $elements = $metas->getElements();
        $titleElement = $this->findElement($elements, 'title');
        $this->assertLessThanOrEqual(53, mb_strlen($titleElement['content'])); // 50-3 + "..."
    }

    public function testSetTitle(): void
    {
        $metas = $this->createMetas();
        $metas->set('title', 'My Page');

        $elements = $metas->getElements();
        $title = $this->findElement($elements, 'title');

        $this->assertNotNull($title);
        $this->assertEquals('title', $title['tag']);
        $this->assertEquals('My Page', $title['content']);
    }

    public function testSetDescription(): void
    {
        $metas = $this->createMetas();
        $metas->set('description', 'Page description');

        $elements = $metas->getElements();
        $desc = $this->findElementByAttr($elements, 'name', 'description');

        $this->assertNotNull($desc);
        $this->assertEquals('Page description', $desc['attrs']['content']);
    }

    public function testSetKeywords(): void
    {
        $metas = $this->createMetas();
        $metas->set('keywords', 'php, html, metas');

        $elements = $metas->getElements();
        $kw = $this->findElementByAttr($elements, 'name', 'keywords');

        $this->assertEquals('php, html, metas', $kw['attrs']['content']);
    }

    public function testSetViewport(): void
    {
        $metas = $this->createMetas();
        $metas->set('viewport', 'width=device-width, initial-scale=1');

        $elements = $metas->getElements();
        $vp = $this->findElementByAttr($elements, 'name', 'viewport');

        $this->assertEquals('width=device-width, initial-scale=1', $vp['attrs']['content']);
    }

    public function testSetRobots(): void
    {
        $metas = $this->createMetas();
        $metas->set('robots', 'index, follow');

        $elements = $metas->getElements();
        $robots = $this->findElementByAttr($elements, 'name', 'robots');

        $this->assertEquals('index, follow', $robots['attrs']['content']);
    }

    public function testSetAuthor(): void
    {
        $metas = $this->createMetas();
        $metas->set('author', 'John Doe');

        $elements = $metas->getElements();
        $author = $this->findElementByAttr($elements, 'name', 'author');

        $this->assertEquals('John Doe', $author['attrs']['content']);
    }

    public function testSetUrl(): void
    {
        $metas = $this->createMetas();
        $metas->set('url', 'https://example.com/page');

        $elements = $metas->getElements();
        $canonical = $this->findElementByAttr($elements, 'rel', 'canonical');

        $this->assertEquals('link', $canonical['tag']);
        $this->assertEquals('https://example.com/page', $canonical['attrs']['href']);
    }

    // ========================================
    // BULK SETS
    // ========================================

    public function testSets(): void
    {
        $metas = $this->createMetas();
        $result = $metas->sets([
            'title' => 'My Title',
            'description' => 'My Description',
            'author' => 'Author',
        ]);

        $elements = $metas->getElements();
        $this->assertCount(3, $elements);
        $this->assertSame($metas, $result);
    }

    // ========================================
    // DIRECT ELEMENT METHODS
    // ========================================

    public function testSetCharset(): void
    {
        $metas = new Metas();
        $metas->setCharset('utf-8');

        $elements = $metas->getElements();
        $charset = $this->findElementByAttr($elements, 'charset', 'utf-8');

        $this->assertNotNull($charset);
        $this->assertEquals('meta', $charset['tag']);
    }

    public function testSetBase(): void
    {
        $metas = new Metas();
        $metas->setBase('https://example.com/');

        $elements = $metas->getElements();
        $base = $this->findElement($elements, 'base');

        $this->assertEquals('https://example.com/', $base['attrs']['href']);
    }

    public function testSetLink(): void
    {
        $metas = new Metas();
        $metas->setLink('canonical', 'https://example.com');

        $elements = $metas->getElements();
        $link = $this->findElementByAttr($elements, 'rel', 'canonical');

        $this->assertEquals('link', $link['tag']);
        $this->assertEquals('https://example.com', $link['attrs']['href']);
    }

    public function testSetMetaHttpEquiv(): void
    {
        $metas = new Metas();
        $metas->setMetaHttpEquiv('X-UA-Compatible', 'IE=edge');

        $elements = $metas->getElements();
        $meta = $this->findElementByAttr($elements, 'http-equiv', 'X-UA-Compatible');

        $this->assertEquals('IE=edge', $meta['attrs']['content']);
    }

    public function testSetMetaName(): void
    {
        $metas = new Metas();
        $metas->setMetaName('custom', 'value', ['extra' => 'attr']);

        $elements = $metas->getElements();
        $meta = $this->findElementByAttr($elements, 'name', 'custom');

        $this->assertEquals('value', $meta['attrs']['content']);
        $this->assertEquals('attr', $meta['attrs']['extra']);
    }

    public function testSetMetaProperty(): void
    {
        $metas = new Metas();
        $metas->setMetaProperty('og:title', 'Title');

        $elements = $metas->getElements();
        $meta = $this->findElementByAttr($elements, 'property', 'og:title');

        $this->assertEquals('Title', $meta['attrs']['content']);
    }

    // ========================================
    // ELEMENT ORDERING
    // ========================================

    public function testElementsAreSorted(): void
    {
        $metas = $this->createMetas();
        $metas->set('description', 'Desc');
        $metas->set('charset', 'utf-8');
        $metas->set('title', 'Title');

        $elements = $metas->getElements();

        // charset(2) < description(20) < title(199)
        $tags = array_map(fn($el) => $el['tag'] . ':' . ($el['attrs']['charset'] ?? $el['attrs']['name'] ?? $el['content'] ?? ''), $elements);
        $charsetIdx = array_search('meta:utf-8', $tags);
        $descIdx = array_search('meta:description', $tags);
        $titleIdx = array_search('title:Title', $tags);

        $this->assertLessThan($descIdx, $charsetIdx);
        $this->assertLessThan($titleIdx, $descIdx);
    }

    // ========================================
    // TEXT CLEANING & TRUNCATION
    // ========================================

    public function testCleanTextStripsHtml(): void
    {
        $metas = $this->createMetas();
        $metas->set('title', '<h1>Hello <b>World</b></h1>');

        $elements = $metas->getElements();
        $title = $this->findElement($elements, 'title');

        $this->assertEquals('Hello World', $title['content']);
    }

    public function testCleanTextRemovesQuotes(): void
    {
        $metas = $this->createMetas();
        $metas->set('title', 'He said "hello"');

        $elements = $metas->getElements();
        $title = $this->findElement($elements, 'title');

        $this->assertStringNotContainsString('"', $title['content']);
    }

    public function testDescriptionTruncated(): void
    {
        $metas = $this->createMetas();
        $longText = str_repeat('word ', 100);
        $metas->set('description', $longText);

        $elements = $metas->getElements();
        $desc = $this->findElementByAttr($elements, 'name', 'description');

        $this->assertLessThanOrEqual(200, mb_strlen($desc['attrs']['content']));
        $this->assertStringEndsWith('...', $desc['attrs']['content']);
    }

    public function testTitleTruncated(): void
    {
        $metas = $this->createMetas();
        $longTitle = str_repeat('word ', 50);
        $metas->set('title', $longTitle);

        $elements = $metas->getElements();
        $title = $this->findElement($elements, 'title');

        $this->assertLessThanOrEqual(90, mb_strlen($title['content']));
        $this->assertStringEndsWith('...', $title['content']);
    }

    public function testNullValueIgnored(): void
    {
        $metas = $this->createMetas();
        $metas->set('title', null);

        $this->assertEmpty($metas->getElements());
    }

    public function testEmptyValueIgnored(): void
    {
        $metas = $this->createMetas();
        $metas->set('title', '');

        $this->assertEmpty($metas->getElements());
    }

    // ========================================
    // NAMESPACES
    // ========================================

    public function testNamespaces(): void
    {
        $metas = new Metas();
        $metas->addNamespace('og: http://ogp.me/ns#');
        $metas->addNamespace('fb: http://ogp.me/ns/fb#');

        $ns = $metas->getNamespaces();
        $this->assertStringContainsString('og: http://ogp.me/ns#', $ns);
        $this->assertStringContainsString('fb: http://ogp.me/ns/fb#', $ns);
    }

    public function testDuplicateNamespacesDeduped(): void
    {
        $metas = new Metas();
        $metas->addNamespace('og: http://ogp.me/ns#');
        $metas->addNamespace('og: http://ogp.me/ns#');

        $this->assertEquals('og: http://ogp.me/ns#', $metas->getNamespaces());
    }

    // ========================================
    // FLUENT INTERFACE
    // ========================================

    public function testFluentInterface(): void
    {
        $metas = new Metas();
        $base = new BaseManager();

        $this->assertSame($metas, $metas->register($base));
        $this->assertSame($metas, $metas->set('title', 'Test'));
        $this->assertSame($metas, $metas->sets(['description' => 'Desc']));
        $this->assertSame($metas, $metas->addNamespace('og:'));
        $this->assertSame($metas, $metas->setCharset('utf-8'));
        $this->assertSame($metas, $metas->setBase('https://x.com'));
        $this->assertSame($metas, $metas->setLink('icon', '/favicon.ico'));
        $this->assertSame($metas, $metas->setMetaName('x', 'y'));
        $this->assertSame($metas, $metas->setMetaProperty('x', 'y'));
        $this->assertSame($metas, $metas->setMetaHttpEquiv('x', 'y'));
        $this->assertSame($metas, $metas->setTitle('T'));
        $this->assertSame($metas, $metas->setTag('custom', 'val'));
    }

    // ========================================
    // HELPERS
    // ========================================

    private function findElement(array $elements, string $tag): ?array
    {
        foreach ($elements as $el) {
            if ($el['tag'] === $tag) {
                return $el;
            }
        }
        return null;
    }

    private function findElementByAttr(array $elements, string $attrKey, string $attrValue): ?array
    {
        foreach ($elements as $el) {
            if (isset($el['attrs'][$attrKey]) && $el['attrs'][$attrKey] === $attrValue) {
                return $el;
            }
        }
        return null;
    }
}
