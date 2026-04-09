<?php

namespace JDZ\Metas\Tests\Manager;

use PHPUnit\Framework\TestCase;
use JDZ\Metas\Metas;
use JDZ\Metas\Manager\OgManager;

class OgManagerTest extends TestCase
{
    private Metas $metas;

    protected function setUp(): void
    {
        $this->metas = new Metas();
        $this->metas->register(new OgManager());
    }

    public function testSetTitleViaManager(): void
    {
        $this->metas->set('title', 'OG Title');

        $elements = $this->metas->getElements();
        $og = $this->findByProperty($elements, 'og:title');

        $this->assertNotNull($og);
        $this->assertEquals('OG Title', $og['attrs']['content']);
    }

    public function testSetUrlAddsOgUrl(): void
    {
        $this->metas->set('url', 'https://example.com');

        $elements = $this->metas->getElements();
        $og = $this->findByProperty($elements, 'og:url');

        $this->assertEquals('https://example.com', $og['attrs']['content']);
    }

    public function testSetDescriptionAddsOgDescription(): void
    {
        $this->metas->set('description', 'OG Desc');

        $elements = $this->metas->getElements();
        $og = $this->findByProperty($elements, 'og:description');

        $this->assertEquals('OG Desc', $og['attrs']['content']);
    }

    public function testOgManagerAddsNamespace(): void
    {
        $this->metas->set('title', 'Test');

        $ns = $this->metas->getNamespaces();
        $this->assertStringContainsString('og: http://ogp.me/ns#', $ns);
    }

    public function testDirectOgMethods(): void
    {
        $og = new OgManager();
        $og->setContainer($this->metas);

        $og->setImage('https://example.com/img.jpg');
        $og->setSiteName('My Site');
        $og->setType('website');
        $og->setLocale('fr_FR');

        $elements = $this->metas->getElements();

        $this->assertNotNull($this->findByProperty($elements, 'og:image'));
        $this->assertNotNull($this->findByProperty($elements, 'og:site_name'));
        $this->assertNotNull($this->findByProperty($elements, 'og:type'));
        $this->assertNotNull($this->findByProperty($elements, 'og:locale'));
    }

    public function testStandardPriceAmountFormats(): void
    {
        $og = new OgManager();
        $og->setContainer($this->metas);
        $og->setStandardPriceAmount('29.9');

        $elements = $this->metas->getElements();
        $price = $this->findByProperty($elements, 'og:price:standard_amount');

        $this->assertEquals('29.90', $price['attrs']['content']);
    }

    private function findByProperty(array $elements, string $property): ?array
    {
        foreach ($elements as $el) {
            if (isset($el['attrs']['property']) && $el['attrs']['property'] === $property) {
                return $el;
            }
        }
        return null;
    }
}
