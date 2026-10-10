<?php

namespace JDZ\Metas\Tests\Manager;

use JDZ\Metas\Manager\TwitterManager;
use JDZ\Metas\Metas;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Twitter cards are `<meta name="twitter:…">` (they were rendered with property=).
 */
class TwitterManagerTest extends TestCase
{
    public static function cards(): array
    {
        return [
            'card' => ['twitter-card', 'summary_large_image', 'twitter:card'],
            'creator' => ['twitter-creator', '@johndoe', 'twitter:creator'],
            'site' => ['twitter-site', '@mysite', 'twitter:site'],
        ];
    }

    #[DataProvider('cards')]
    public function testATwitterCardIsANamedMeta(string $key, string $value, string $name): void
    {
        $metas = new Metas();
        $metas->register(new TwitterManager());
        $metas->set($key, $value);

        $attrs = array_column($metas->getElements(), 'attrs');

        $this->assertContains(['name' => $name, 'content' => $value], $attrs);
    }
}
