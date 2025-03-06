# mJDZ Metas manager

Utility to manage the head metas of a web page

## Installation

To install the library, use Composer:

```sh
composer require jdz/metas
```

## Usage
Here is an example of how to use the JDZ Metas manager:

```php
<?php
$standalone = true;
$cacheSupport = true;
$appleSupport = true;
$msieSupport = true;
$baseUrl = 'https://localhost';

$data = [];

# base 
$data['title'] = 'Page title';
$data['base'] = $baseUrl;
$data['charset'] = 'utf-8';
$data['ua-compatible'] = 'IE=edge';
$data['content-style-type'] = '';
$data['content-script-type'] = '';
$data['fragment'] = '!';
$data['viewport'] = 'width=device-width, initial-scale=1';
$data['theme-light-color'] = '#888';
$data['theme-dark-color'] = '#222';

if (true === $cacheSupport) {
    $data['cache-control'] = 'private, max-age=86400';
} else {
    $data['cache-control'] = 'no-cache, no-store, must-revalidate';
}

# base,og
$data['title'] = 'Page title';
$data['url'] = $baseUrl . 'page-url/';
$data['description'] = 'My page description';

# base,google
$data['robots'] = 'noindex,nofollow';

# base,article
$data['author'] = 'Page author';

# base,msie
$data['theme-color'] = '#F9F9F9';

# base,apple
$data['application-name'] = 'AppName';
if (false === $standalone) {
    $data['mobile-web-app-capable'] = 'yes';
}

# google
$data['google'] = '';
$data['google-site-verification'] = '';

# og
$data['image'] = $baseUrl . 'image.jpg';
$data['site-name'] = 'My own website';
$data['type'] = 'website';
$data['locale'] = 'fr-FR';
$data['brand'] = 'My Brand';
$data['availability'] = '';
$data['standard-price-amount'] = '';

# facebook
$data['facebook-admins'] = '';
$data['facebook-app-id'] = '';

# twitter
$data['twitter-card'] = 'summary';
$data['twitter-creator'] = '';
$data['twitter-site'] = '';

# pinterest
$data['pinterest'] = '';
$data['pinterest-domain-verify'] = '';
$data['pinterest-block-rich-pins'] = '';

# product
$data['plural-title'] = '';
$data['price-amount'] = '';
$data['price-currency'] = '';

# article 
$data['section'] = '';
$data['published-time'] = '';
$data['modified-time'] = '';

# msie
if (true === $msieSupport) {
    $data['msapplication-config'] = $baseUrl . 'browserconfig.xml';
} else {
    $data['msapplication-config'] = 'none';
}

# apple
if (true === $appleSupport) {
    $data['mobile-web-app-title'] = 'MyApp';
    $data['mobile-web-app-status-bar-style'] = 'black';
}

try {
    $metas = new \JDZ\Metas\Metas();
    $metas->register(new \JDZ\Metas\Manager\BaseManager());
    $metas->register(new \JDZ\Metas\Manager\AppleManager($appleSupport));
    $metas->register(new \JDZ\Metas\Manager\MsManager($msieSupport));
    $metas->register(new \JDZ\Metas\Manager\GoogleManager());
    $metas->register(new \JDZ\Metas\Manager\OgManager());
    // ... article, facebook, pinterest, product, twitter
    $metas->sets($data);

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
```

## License
This project is licensed under the MIT License - see the LICENSE file for details.

## Author
Joffrey Demetz - joffreydemetz.com