<?php

/**
 * @author    Joffrey Demetz <joffrey.demetz@gmail.com>
 * @license   MIT License; <https://opensource.org/licenses/MIT>
 */

namespace JDZ\Metas\Manager;

use JDZ\Metas\Metas;

interface ManagerInterface
{
  public function setContainer(Metas $container);
}
