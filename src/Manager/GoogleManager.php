<?php

/**
 * (c) Joffrey Demetz <joffrey.demetz@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JDZ\Metas\Manager;

class GoogleManager extends Manager
{
  public function setGoogleSiteVerification(string $googleSiteVerification)
  {
    $this->container->setMetaName('google-site-verification', $googleSiteVerification);
    return $this;
  }

  public function setRobots(string $robots)
  {
    // $this->container->setMetaName('googlebot', $robots);
    return $this;
  }

  public function setGoogle(string $value)
  {
    $this->container->setMetaName('google', $value);
    return $this;
  }
}
