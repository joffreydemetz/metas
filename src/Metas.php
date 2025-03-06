<?php

/**
 * (c) Joffrey Demetz <joffrey.demetz@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JDZ\Metas;

use JDZ\Metas\Manager\ManagerInterface;

class Metas
{
  const TITLE_LIMIT_LENGTH = 90;
  const DESCRIPTION_LIMIT_LENGTH = 200;

  private array $config;

  private array $managers = [];
  private array $elements = [];
  private array $ns = [];

  public function __construct(array $config = [])
  {
    $this->config = array_merge([
      'title_limit' => self::TITLE_LIMIT_LENGTH,
      'seo-title_limit' => self::TITLE_LIMIT_LENGTH,
      'description_limit' => self::DESCRIPTION_LIMIT_LENGTH,
    ], $config);
  }

  public function addNamespace(string $ns)
  {
    $this->ns[] = trim($ns);
    return $this;
  }

  public function getNamespaces()
  {
    return implode(' ', \array_values(\array_unique($this->ns)));
  }

  public function register(ManagerInterface $manager)
  {
    $manager->setContainer($this);
    $this->managers[] = $manager;
    return $this;
  }

  public function sets(array $properties)
  {
    foreach ($properties as $key => $value) {
      $this->set($key, $value);
    }
    return $this;
  }

  public function set(string $key, ?string $value)
  {
    if ($value) {
      if ($value = $this->cleanText($value)) {
        if (isset($this->config[$key . '_limit'])) {
          $value = $this->cutText($value, $this->config[$key . '_limit']);
        }

        $method = str_replace(' ', '', ucwords(str_replace(array('-', '_'), ' ', 'set-' . $key)));

        foreach ($this->managers as $manager) {
          if (method_exists($manager, $method)) {
            $manager->{$method}($value);
          }
        }
      }
    }

    return $this;
  }

  public function setTag(string $tag, string $content, array $attrs = [])
  {
    $position = $this->guessElementPosition($tag);

    $this->elements[$position] = [
      'tag' => $tag,
      'closed' => true,
      'content' => $content,
      'attrs' => $attrs,
    ];

    return $this;
  }

  public function setBase(string $href)
  {
    $position = $this->guessElementPosition('base');

    $this->elements[$position] = [
      'tag' => 'base',
      'attrs' => [
        'href' => $href,
      ],
    ];

    return $this;
  }

  public function setTitle(string $title)
  {
    $this->setTag('title', $title);
    return $this;
  }

  public function setMetaName(string $name, string $content, array $attrs = [])
  {
    $position = $this->guessElementPosition($name);

    $attrs = array_merge([
      'name' => $name,
      'content' => $content,
    ], $attrs);

    $this->elements[$position] = [
      'tag' => 'meta',
      'attrs' => $attrs,
    ];

    return $this;
  }

  public function setMetaProperty(string $property, string $content, array $attrs = [])
  {
    $position = $this->guessElementPosition($property);

    $attrs = array_merge([
      'property' => $property,
      'content' => $content,
    ], $attrs);

    $this->elements[$position] = [
      'tag' => 'meta',
      'attrs' => $attrs,
    ];

    return $this;
  }

  public function setMetaHttpEquiv(string $httpEquiv, string $content)
  {
    $position = $this->guessElementPosition($httpEquiv);

    $this->elements[$position] = [
      'tag' => 'meta',
      'attrs' => [
        'http-equiv' => $httpEquiv,
        'content' => $content,
      ],
    ];

    return $this;
  }

  public function setCharset(string $charset)
  {
    $position = $this->guessElementPosition('charset');

    $this->elements[$position] = [
      'tag' => 'meta',
      'attrs' => [
        'charset' => $charset,
      ],
    ];

    return $this;
  }

  public function setLink(string $rel, string $href)
  {
    $position = $this->guessElementPosition($rel);

    $this->elements[$position] = [
      'tag' => 'link',
      'attrs' => [
        'rel' => $rel,
        'href' => $href,
      ],
    ];

    return $this;
  }

  public function getElements(): array
  {
    \ksort($this->elements);
    return \array_values($this->elements);
  }

  private function guessElementPosition(string $element, bool $multiple = false): string
  {
    $position = 0;
    $minimum  = 500;
    $arrayPosition = 1;

    if (preg_match("/og:.+/", $element)) {
      $minimum = 300;
    } elseif (preg_match("/^twitter:.+$/", $element)) {
      $minimum = 350;
    } elseif (preg_match("/^product:.+$/", $element)) {
      $minimum = 400;
    } elseif ('theme-color' === $element) {
      $multiple = true;
      $position = 130;
    } else {
      switch ($element) {
        case 'base':
          $position = 1;
          break;

        case 'charset':
          $position = 2;
          break;

        case 'viewport':
          $position = 3;
          break;

        case 'fragment':
          $position = 4;
          break;

        case 'X-UA-Compatible':
          $position = 8;
          break;

        case 'Content-Style-Type':
          $position = 9;
          break;

        case 'Content-Script-Type':
          $position = 10;
          break;

        case 'canonical':
          $position = 11;
          break;

        case 'robots':
          $position = 12;
          break;

        case 'googlebot':
          $position = 13;
          break;

        case 'description':
          $position = 20;
          break;

        case 'keywords':
          $position = 21;
          break;

        case 'author':
          $position = 22;
          break;

        case 'generator':
          $position = 23;
          break;

        case 'expires':
          $position = 30;
          break;

        case 'cache-control':
          $position = 31;
          break;

        case 'msapplication-tilecolor':
          $position = 40;
          break;

        case 'image_src':
          $position = 41;
          break;

        case 'application-name':
          $position = 100;
          break;

        case 'mobile-web-app-capable':
          $position = 101;
          break;

        case 'apple-mobile-web-app-title':
          $position = 110;
          break;

        case 'apple-mobile-web-app-capable':
          $position = 111;
          break;

        case 'apple-mobile-web-app-status-bar-style':
          $position = 112;
          break;

        case 'msapplication-config':
          $position = 113;
          break;

        case 'msapplication-titlecolor':
          $position = 114;
          break;

        case 'google-site-verification':
          $position = 120;
          break;

        case 'google':
          $position = 121;
          break;

        case 'title':
          $position = 199;
          break;

        case 'prefetch':
          $position = 200;
          break;

        case 'preconnect':
          $position = 201;
          break;
      }
    }

    if (0 === $position) {
      $positionKeys = [];
      $elementKeys = [];
      foreach ($this->elements as $key => $value) {
        \preg_match("/^(\d+)([^\d]+)(\d+)?$/", $key, $m);
        $positionKeys[] = $m[1];
        $elementKeys[$m[1]] = $m[1] . $m[2];
      }

      for ($_position = $minimum; $_position < 1000; $_position++) {
        if (in_array(\str_pad($_position, 3, '0', \STR_PAD_LEFT) . $element, \array_values($elementKeys))) {
          $position = $_position;
          break;
        }

        if (in_array(\str_pad($_position, 3, '0', \STR_PAD_LEFT), $positionKeys)) {
          continue;
        }

        $position = $_position;
        break;
      }
    }

    // for metas arrays
    if (true === $multiple) {
      $pos = false;
      while (!$pos && $arrayPosition < 10) {
        $_pos = str_pad($position, 3, '0', STR_PAD_LEFT) . $element . $arrayPosition;

        if (!isset($this->elements[$_pos])) {
          $pos = $_pos;
          break;
        }

        $arrayPosition++;
      }

      if (false === $pos) {
        throw new \Exception('Not more than 10 elements per meta tag');
      }
    } else {
      $pos = \str_pad($position, 3, '0', \STR_PAD_LEFT) . $element;
    }

    return $pos;
  }

  private function cleanText(string $str): string
  {
    $str = \html_entity_decode($str);
    $str = \strip_tags($str);
    $str = \htmlentities($str);
    $str = \preg_replace("/[\r\n\s]+/", ' ', $str);
    $str = \html_entity_decode($str);
    $str = str_replace('"', ' ', $str);
    $str = trim($str);
    return $str;
  }

  private function cutText(string $text, string|int $key): string
  {
    if ('' === $text) {
      return $text;
    }

    if (\is_int($key)) {
      $limit = $key;
    } elseif (isset($this->config[$key . '_limit'])) {
      $limit = $this->config[$key . '_limit'];
    } else {
      return $text;
    }

    $length = \mb_strlen($text);

    if ($length <= (int) $limit) {
      return $text;
    }

    $text = \mb_substr($text, 0, ($limit -= 3));

    if ($space = \mb_strrpos($text, ' ')) {
      $text = \mb_substr($text, 0, $space);
    }

    return $text . '...';
  }
}
