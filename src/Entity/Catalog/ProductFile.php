<?php namespace App\Entity\Catalog;

use Doctrine\ORM\Mapping as ORM;
use Vankosoft\CatalogBundle\Model\ProductFile as BaseProductFile;

/**
 * @Doctrine\Common\Annotations\Annotation\IgnoreAnnotation( "ORM\MappedSuperclass" )
 * @Doctrine\Common\Annotations\Annotation\IgnoreAnnotation("ORM\Column")
 */
#[ORM\Entity]
#[ORM\Table(name: "VSCAT_ProductFiles")]
class ProductFile extends BaseProductFile
{
    /** @var string */
    #[ORM\Column(type: "string", length: 255)]
    private $locale;
    
    public function getLocale(): ?string
    {
        return $this->locale;
    }
    
    public function setLocale($locale)
    {
        $this->locale   = $locale;
        
        return $this;
    }
}
