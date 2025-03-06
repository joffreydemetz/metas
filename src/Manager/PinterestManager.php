<?php

/**
 * (c) Joffrey Demetz <joffrey.demetz@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JDZ\Metas\Manager;

class PinterestManager extends Manager
{
  public function setPinterest(string $pinterest, string $message = '')
  {
    $attrs = [];

    if ('' !== $message) {
      $attrs['description'] = $message;
    }

    $this->container->setMetaName('pinterest', $pinterest, $attrs);
    return $this;
  }

  public function setPinterestDomainVerify(string $domainVerify)
  {
    $this->container->setMetaName('p:domain_verify', $domainVerify);
    return $this;
  }

  public function setPinterestBlockRichPins(string $blockRichPins)
  {
    $this->container->setMetaName('pinterest-rich-pin', 'false');
    return $this;
  }
}
