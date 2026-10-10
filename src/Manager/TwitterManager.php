<?php

/**
 * (c) Joffrey Demetz <joffrey.demetz@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JDZ\Metas\Manager;

class TwitterManager extends Manager
{
  public function setTwitterCard(string $card)
  {
    $this->container->setMetaName('twitter:card', $card);
    return $this;
  }

  public function setTwitterCreator(string $creator)
  {
    $this->container->setMetaName('twitter:creator', $creator);
    return $this;
  }

  public function setTwitterSite(string $site)
  {
    $this->container->setMetaName('twitter:site', $site);
    return $this;
  }
}
