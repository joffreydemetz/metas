<?php

/**
 * Example 1
 */

require_once realpath(__DIR__ . '/../vendor/autoload.php');

use JDZ\Utils\Data as jData;

$standalone = true;
$cacheSupport = true;
$appleSupport = true;
$msieSupport = false;
$baseUrl = 'https://localhost/';

$data = new jData();

# base 
$data->set('title', 'Page title');
$data->set('base', $baseUrl);
$data->set('charset', 'utf-8');
$data->set('ua-compatible', 'IE=edge');
$data->set('content-style-type', '');
$data->set('content-script-type', '');
$data->set('fragment', '!');
$data->set('keywords', '');
$data->set('generator', '');
$data->set('viewport', 'width=device-width, initial-scale=1');
$data->set('theme-light-color', '#888');
$data->set('theme-dark-color', '#222');
$data->set('prefetch', '');
$data->set('preconnect', '');

if (true === $cacheSupport) {
    $data->set('cache-control', 'private, max-age=86400');
    $data->set('expires', '');
} else {
    $data->set('cache-control', 'no-cache, no-store, must-revalidate');
}

# base,og
$data->set('title', 'Page title');
$data->set('url', $baseUrl . 'page-url/');
$data->set('description', 'My page description');

# base,google
$data->set('robots', 'noindex,nofollow');

# base,article
$data->set('author', '');

# base,msie
$data->set('theme-color', '#F9F9F9');

# base,apple
$data->set('application-name', 'AppName');
if (false === $standalone) {
    $data->set('mobile-web-app-capable', 'yes');
}

# google
$data->set('google', '');
$data->set('google-site-verification', '');

# og
$data->set('image', $baseUrl . 'image.jpg');
$data->set('site-name', 'My own website');
$data->set('type', 'website');
$data->set('locale', 'fr-FR');
$data->set('brand', '');
$data->set('availability', '');
$data->set('standard-price-amount', '');

# facebook
$data->set('facebook-admins', '');
$data->set('facebook-app-id', '');

# twitter
$data->set('twitter-card', 'summary');
$data->set('twitter-creator', '');
$data->set('twitter-site', '');

# pinterest
$data->set('pinterest', '');
$data->set('pinterest-domain-verify', '');
$data->set('pinterest-block-rich-pins', '');

# product
$data->set('plural-title', '');
$data->set('price-amount', '');
$data->set('price-currency', '');

# article 
$data->set('section', '');
$data->set('published-time', '');
$data->set('modified-time', '');

# msie
if (true === $msieSupport) {
    $data->set('msapplication-config', $baseUrl . 'browserconfig.xml');
} else {
    $data->set('msapplication-config', 'none');
}

# apple
if (true === $appleSupport) {
    $data->set('mobile-web-app-title', 'MyApp');
    $data->set('mobile-web-app-status-bar-style', 'black');
}

try {
    $metas = new \JDZ\Metas\Metas();
    $metas->register(new \JDZ\Metas\Manager\BaseManager());
    $metas->register(new \JDZ\Metas\Manager\AppleManager($appleSupport));
    $metas->register(new \JDZ\Metas\Manager\MsManager($msieSupport));
    $metas->register(new \JDZ\Metas\Manager\GoogleManager());
    $metas->register(new \JDZ\Metas\Manager\OgManager());
    // $metas->register(new \JDZ\Metas\Manager\TwitterManager());
    // $metas->register(new \JDZ\Metas\Manager\FacebookManager());
    // $metas->register(new \JDZ\Metas\Manager\ArticleManager());
    // $metas->register(new \JDZ\Metas\Manager\PinterestManager());
    $metas->sets($data->all());

    $documentHead = $metas->getElements();
    $documentNamespaces = $metas->getNamespaces();

    function printHead(array $elements): string
    {
        $head = '';
        foreach ($elements as $element) {
            $head .= '<' . $element['tag'];
            foreach ($element['attrs'] as $key => $value) {
                $head .= ' ' . $key . '="' . $value . '"';
            }

            if (!empty($element['closed'])) {
                $head .= '>';
                if (!empty($element['content'])) {
                    $head .= $element['content'];
                }
                $head .= '</' . $element['tag'] . '>' . "\n";
            } else {
                $head .= ' />' . "\n";
            }
        }
        return $head;
    }

    echo 'HTML namespaces' . "\n\n";
    echo '<html prefix="' . $documentNamespaces . '"> ' . "\n\n";

    echo '<head>' . "\n\n";
    echo printHead($documentHead);
} catch (\Throwable $e) {
    echo $e->getMessage();
}
exit();
