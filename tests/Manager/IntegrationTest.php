<?php

namespace JDZ\Metas\Tests\Manager;

use PHPUnit\Framework\TestCase;
use JDZ\Metas\Metas;
use JDZ\Metas\Manager\BaseManager;
use JDZ\Metas\Manager\OgManager;
use JDZ\Metas\Manager\TwitterManager;
use JDZ\Metas\Manager\ArticleManager;
use JDZ\Metas\Manager\ProductManager;
use JDZ\Metas\Manager\AppleManager;
use JDZ\Metas\Manager\FacebookManager;
use JDZ\Metas\Manager\GoogleManager;
use JDZ\Metas\Manager\MsManager;
use JDZ\Metas\Manager\PinterestManager;

class IntegrationTest extends TestCase
{
    public function testFullPageMetaSetup(): void
    {
        $metas = new Metas();
        $metas->register(new BaseManager());
        $metas->register(new OgManager());
        $metas->register(new TwitterManager());

        $metas->sets([
            'charset' => 'utf-8',
            'viewport' => 'width=device-width, initial-scale=1',
            'title' => 'My Page Title',
            'description' => 'A great page about things',
            'url' => 'https://example.com/page',
            'author' => 'John Doe',
            'robots' => 'index, follow',
            'twitter-card' => 'summary',
            'twitter-site' => '@mysite',
        ]);

        $elements = $metas->getElements();

        // Should have: charset, viewport, canonical, robots, description, author, title, og:title, og:url, og:description, twitter:card, twitter:site
        $this->assertGreaterThanOrEqual(10, count($elements));

        // Verify namespaces
        $this->assertStringContainsString('og: http://ogp.me/ns#', $metas->getNamespaces());
    }

    public function testSetDispatchesToMultipleManagers(): void
    {
        $metas = new Metas();
        $metas->register(new BaseManager());
        $metas->register(new OgManager());

        $metas->set('title', 'Shared Title');

        $elements = $metas->getElements();

        // Should have both <title> and <meta property="og:title">
        $hasTitle = false;
        $hasOgTitle = false;
        foreach ($elements as $el) {
            if ($el['tag'] === 'title') $hasTitle = true;
            if (isset($el['attrs']['property']) && $el['attrs']['property'] === 'og:title') $hasOgTitle = true;
        }

        $this->assertTrue($hasTitle);
        $this->assertTrue($hasOgTitle);
    }

    public function testArticleManager(): void
    {
        $metas = new Metas();
        $article = new ArticleManager();
        $metas->register($article);

        $article->setAuthor('John');
        $article->setSection('Tech');
        $article->setPublishedTime('2024-01-01');
        $article->setModifiedTime('2024-06-01');

        $elements = $metas->getElements();
        $this->assertCount(4, $elements);
    }

    public function testProductManager(): void
    {
        $metas = new Metas();
        $product = new ProductManager();
        $metas->register($product);

        $product->setPluralTitle('Widgets');
        $product->setPriceAmount('29.9');
        $product->setPriceCurrency('EUR');

        $elements = $metas->getElements();
        $this->assertCount(3, $elements);

        // Check price formatting
        $price = null;
        foreach ($elements as $el) {
            if (isset($el['attrs']['property']) && $el['attrs']['property'] === 'product:price.amount') {
                $price = $el;
            }
        }
        $this->assertEquals('29.90', $price['attrs']['content']);
    }

    public function testAppleManager(): void
    {
        $metas = new Metas();
        $apple = new AppleManager(true);
        $metas->register($apple);

        $metas->set('application-name', 'My App');

        $elements = $metas->getElements();
        // With appleAppSupport=true, setting applicationName also sets capable + status bar
        $this->assertGreaterThanOrEqual(2, count($elements));
    }

    public function testFacebookManager(): void
    {
        $metas = new Metas();
        $fb = new FacebookManager();
        $metas->register($fb);

        $fb->setFacebookAppId('123456');
        $fb->setFacebookAdmins('admin1');

        $ns = $metas->getNamespaces();
        $this->assertStringContainsString('fb: http://ogp.me/ns/fb#', $ns);
    }

    public function testGoogleManager(): void
    {
        $metas = new Metas();
        $google = new GoogleManager();
        $metas->register($google);

        $metas->set('google-site-verification', 'abc123');

        $elements = $metas->getElements();
        $this->assertCount(1, $elements);
    }

    public function testMsManager(): void
    {
        $metas = new Metas();
        $ms = new MsManager(true);
        $metas->register($ms);

        $ms->setMsApplicationConfig('/browserconfig.xml');

        $elements = $metas->getElements();
        $this->assertGreaterThanOrEqual(1, count($elements));
    }

    public function testPinterestManager(): void
    {
        $metas = new Metas();
        $pinterest = new PinterestManager();
        $metas->register($pinterest);

        $pinterest->setPinterest('nopin', 'Please do not pin');
        $pinterest->setPinterestDomainVerify('abc123');

        $elements = $metas->getElements();
        $this->assertCount(2, $elements);
    }
}
