<?php

namespace JDZ\Metas\Tests\Manager;

use PHPUnit\Framework\TestCase;
use JDZ\Metas\Metas;
use JDZ\Metas\Manager\TwitterManager;

class TwitterManagerTest extends TestCase
{
    private Metas $metas;

    protected function setUp(): void
    {
        $this->metas = new Metas();
        $this->metas->register(new TwitterManager());
    }

    public function testSetTwitterCard(): void
    {
        $this->metas->set('twitter-card', 'summary_large_image');

        $elements = $this->metas->getElements();
        $card = $this->findByProperty($elements, 'twitter:card');

        $this->assertNotNull($card);
        $this->assertEquals('summary_large_image', $card['attrs']['content']);
    }

    public function testSetTwitterCreator(): void
    {
        $this->metas->set('twitter-creator', '@johndoe');

        $elements = $this->metas->getElements();
        $creator = $this->findByProperty($elements, 'twitter:creator');

        $this->assertEquals('@johndoe', $creator['attrs']['content']);
    }

    public function testSetTwitterSite(): void
    {
        $this->metas->set('twitter-site', '@mysite');

        $elements = $this->metas->getElements();
        $site = $this->findByProperty($elements, 'twitter:site');

        $this->assertEquals('@mysite', $site['attrs']['content']);
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
