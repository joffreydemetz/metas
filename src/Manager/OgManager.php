<?php

/**
 * (c) Joffrey Demetz <joffrey.demetz@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JDZ\Metas\Manager;

class OgManager extends Manager
{
  public function setTitle(string $title)
  {
    $this->container->addNamespace('og: http://ogp.me/ns#');
    $this->container->setMetaProperty('og:title', $title);
    return $this;
  }

  public function setUrl(string $url)
  {
    $this->container->addNamespace('og: http://ogp.me/ns#');
    $this->container->setMetaProperty('og:url', $url);
    return $this;
  }

  public function setDescription(string $description)
  {
    $this->container->addNamespace('og: http://ogp.me/ns#');
    $this->container->setMetaProperty('og:description', $description);
    return $this;
  }

  public function setImage(string $image)
  {
    $this->container->addNamespace('og: http://ogp.me/ns#');
    $this->container->setMetaProperty('og:image', $image);
    return $this;
  }

  public function setSiteName(string $siteName)
  {
    $this->container->addNamespace('og: http://ogp.me/ns#');
    $this->container->setMetaProperty('og:site_name', $siteName);
    return $this;
  }

  public function setType(string $type)
  {
    $this->container->addNamespace('og: http://ogp.me/ns#');
    $this->container->setMetaProperty('og:type', $type);
    return $this;
  }

  public function setLocale(string $locale)
  {
    $this->container->addNamespace('og: http://ogp.me/ns#');
    $this->container->setMetaProperty('og:locale', $locale);
    return $this;
  }

  public function setBrand(string $brand)
  {
    $this->container->addNamespace('og: http://ogp.me/ns#');
    $this->container->setMetaProperty('og:brand', $brand);
    return $this;
  }

  public function setAvailibility(string $availability)
  {
    $this->container->addNamespace('og: http://ogp.me/ns#');
    $this->container->setMetaProperty('og:availability', $availability);
    return $this;
  }

  public function setStandardPriceAmount(string $price)
  {
    $price = number_format((float)$price, 2, '.', '');
    $this->container->addNamespace('og: http://ogp.me/ns#');
    $this->container->setMetaProperty('og:price:standard_amount', $price);
    return $this;
  }
}
