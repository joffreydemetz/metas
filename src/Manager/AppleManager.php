<?php

/**
 * @author    Joffrey Demetz <joffrey.demetz@gmail.com>
 * @license   MIT License; <https://opensource.org/licenses/MIT>
 */

namespace JDZ\Metas\Manager;

class AppleManager extends Manager
{
  protected bool $appleAppSupport;

  public function __construct(bool $appleAppSupport = false)
  {
    $this->appleAppSupport = $appleAppSupport;
  }

  public function setApplicationName(string $applicationName)
  {
    $this->container->setMetaName('apple-mobile-web-app-title', $applicationName);

    if (true === $this->appleAppSupport) {
      $this->setMobileWebAppCapable('yes');
      $this->setAppleMobileWebAppStatusBarStyle('black');
    }

    return $this;
  }

  public function setMobileWebAppCapable(string $webAppCapable)
  {
    $this->container->setMetaName('apple-mobile-web-app-capable', $webAppCapable);
    return $this;
  }

  public function setAppleMobileWebAppStatusBarStyle(string $webAppStatusBarStyle)
  {
    $this->container->setMetaName('apple-mobile-web-app-status-bar-style', $webAppStatusBarStyle);
    return $this;
  }
}
