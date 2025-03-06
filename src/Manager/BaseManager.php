<?php

/**
 * (c) Joffrey Demetz <joffrey.demetz@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JDZ\Metas\Manager;

class BaseManager extends Manager
{
  public function setTitle(string $title)
  {
    $this->container->setTag('title', $title);
    return $this;
  }

  public function setBase(string $href)
  {
    $this->container->setBase($href);
    return $this;
  }

  public function setCharset(string $charset)
  {
    $this->container->setCharset($charset);
    return $this;
  }

  public function setXUaCompatible(string $content)
  {
    $this->container->setMetaHttpEquiv('X-UA-Compatible', $content);
    return $this;
  }

  public function setCacheControl(string $policy)
  {
    $this->container->setMetaHttpEquiv('cache-control', $policy);
    return $this;
  }

  public function setExpires(string $expires)
  {
    $this->container->setMetaHttpEquiv('expires', $expires);
    return $this;
  }

  public function setContentStyleType(string $styleType)
  {
    $this->container->setMetaHttpEquiv('Content-Style-Type', $styleType);
    return $this;
  }

  public function setContentScriptType(string $scriptType)
  {
    $this->container->setMetaHttpEquiv('Content-Script-Type', $scriptType);
    return $this;
  }

  public function setFragment(string $fragment)
  {
    $this->container->setMetaName('fragment', $fragment);
    return $this;
  }

  public function setDescription(string $description)
  {
    $this->container->setMetaName('description', $description);
    return $this;
  }

  public function setKeywords(string $keywords)
  {
    $this->container->setMetaName('keywords', $keywords);
    return $this;
  }

  public function setGenerator(string $generator)
  {
    $this->container->setMetaName('generator', $generator);
    return $this;
  }

  public function setRobots(string $robots)
  {
    $this->container->setMetaName('robots', $robots);
    return $this;
  }

  public function setViewport(string $viewport)
  {
    $this->container->setMetaName('viewport', $viewport);
    return $this;
  }

  public function setAuthor(string $author)
  {
    $this->container->setMetaName('author', $author);
    return $this;
  }

  public function setUrl(string $url)
  {
    $this->container->setLink('canonical', $url);
    return $this;
  }

  public function setApplicationName(string $applicationName)
  {
    $this->container->setMetaName('application-name', $applicationName);
    return $this;
  }

  public function setMobileWebAppCapable(string $webAppCapable)
  {
    $this->container->setMetaName('mobile-web-app-capable', $webAppCapable);
    return $this;
  }

  public function setThemeColor(string $themeColor)
  {
    $this->container->setMetaName('theme-color', $themeColor);
    return $this;
  }

  public function setThemeLightColor(string $themeColor)
  {
    $this->container->setMetaName('theme-color', $themeColor, ['media' => '(prefers-color-scheme: light)']);
    return $this;
  }

  public function setThemeDarkColor(string $themeColor)
  {
    $this->container->setMetaName('theme-color', $themeColor, ['media' => '(prefers-color-scheme: dark)']);
    return $this;
  }

  public function setPrefetch(string $url)
  {
    $this->container->setLink('prefetch', $url);
    return $this;
  }

  public function setPreconnect(string $url)
  {
    $this->container->setLink('preconnect', $url);
    return $this;
  }
}
