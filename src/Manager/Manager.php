<?php

/**
 * @author    Joffrey Demetz <joffrey.demetz@gmail.com>
 * @license   MIT License; <https://opensource.org/licenses/MIT>
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
