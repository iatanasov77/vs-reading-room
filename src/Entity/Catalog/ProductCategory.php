<?php namespace App\Entity\Catalog;

use Doctrine\ORM\Mapping as ORM;
use Vankosoft\CatalogBundle\Model\ProductCategory as ProductCategoryBase;

#[ORM\Entity]
#[ORM\Table(name: "VSCAT_ProductCategories")]
class ProductCategory extends ProductCategoryBase
{
    public function __toString()
    {
        return $this->getNameTranslated( 'en_US' );
        //return $this->taxon ? $this->taxon->getName() : '';
    }
}
