<?php

/**
 * (c) Joffrey Demetz <joffrey.demetz@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JDZ\Metas\Manager;

class FacebookManager extends Manager
{
  public function setFacebookAdmins(string $admins)
  {
    $this->container->addNamespace('fb: http://ogp.me/ns/fb#');
    $this->container->setMetaProperty('fb:admins', $admins);
    return $this;
  }
  public function setFacebookAppId(string $app_id)
  {
    $this->container->addNamespace('fb: http://ogp.me/ns/fb#');
    $this->container->setMetaProperty('fb:app_id', $app_id);
    return $this;
  }
}
