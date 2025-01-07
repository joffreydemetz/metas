<?php

/**
 * @author    Joffrey Demetz <joffrey.demetz@gmail.com>
 * @license   MIT License; <https://opensource.org/licenses/MIT>
 */

namespace JDZ\Metas\Manager;

class MsManager extends Manager
{
  protected bool $msBrowserConfigSupport;

  public function __construct(bool $msBrowserConfigSupport = false)
  {
    $this->msBrowserConfigSupport = $msBrowserConfigSupport;
  }

  public function setMsApplicationConfig(string $msconfig)
  {
    if (true === $this->msBrowserConfigSupport) {
      $this->container->setMetaName('msapplication-config', $msconfig);
      $this->setThemeColor('#F9F9F9');
    } else {
      $this->container->setMetaName('msapplication-config', 'none');
    }
    return $this;
  }

  public function setThemeColor(string $themeColor)
  {
    if (true === $this->msBrowserConfigSupport) {
      $this->container->setMetaName('msapplication-tilecolor', $themeColor);
    }
    return $this;
  }
}
