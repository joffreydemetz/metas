<?php

/**
 * (c) Joffrey Demetz <joffrey.demetz@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JDZ\Metas\Manager;

class ArticleManager extends Manager
{
  public function setAuthor(string $author)
  {
    $this->container->setMetaProperty('article:author', $author);
    return $this;
  }

  public function setSection(string $section)
  {
    $this->container->setMetaProperty('article:section', $section);
    return $this;
  }

  public function setPublishedTime(string $publishedTime)
  {
    $this->container->setMetaProperty('article:published_time', $publishedTime);
    return $this;
  }

  public function setModifiedTime(string $modifiedTime)
  {
    $this->container->setMetaProperty('article:modified_time', $modifiedTime);
    return $this;
  }
}
