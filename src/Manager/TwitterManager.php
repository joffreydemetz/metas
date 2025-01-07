<?php

/**
 * @author    Joffrey Demetz <joffrey.demetz@gmail.com>
 * @license   MIT License; <https://opensource.org/licenses/MIT>
 */

namespace JDZ\Metas\Manager;

class TwitterManager extends Manager
{
  public function setTwitterCard(string $card)
  {
    $this->container->setMetaProperty('twitter:card', $card);
    return $this;
  }

  public function setTwitterCreator(string $creator)
  {
    $this->container->setMetaProperty('twitter:creator', $creator);
    return $this;
  }

  public function setTwitterSite(string $site)
  {
    $this->container->setMetaProperty('twitter:site', $site);
    return $this;
  }
}
