<?php

/**
 * @author    Joffrey Demetz <joffrey.demetz@gmail.com>
 * @license   MIT License; <https://opensource.org/licenses/MIT>
 */

namespace JDZ\Metas\Manager;

class ProductManager extends Manager
{
  public function setPluralTitle(string $plural_title)
  {
    $this->container->setMetaProperty('product:plural_title', $plural_title);
    return $this;
  }

  public function setPriceAmount(string $price)
  {
    $price = number_format((float)$price, 2, '.', '');
    $this->container->setMetaProperty('product:price.amount', $price);
    return $this;
  }

  public function setPriceCurrency(string $currency)
  {
    $this->container->setMetaProperty('product:price.currency', $currency);
    return $this;
  }
}
