<?php

/**
 * (c) Joffrey Demetz <joffrey.demetz@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JDZ\Metas\Manager;

use JDZ\Metas\Metas;

class Manager implements ManagerInterface
{
  protected $container;

  public function setContainer(Metas $container)
  {
    $this->container = $container;
    return $this;
  }
}
