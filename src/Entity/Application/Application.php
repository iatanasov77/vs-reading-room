<?php namespace App\Entity\Application;

use Doctrine\ORM\Mapping as ORM;
use Vankosoft\ApplicationBundle\Model\Application as BaseApplication;
use App\Entity\ReadingRoomApplication;

/**
 * @Doctrine\Common\Annotations\Annotation\IgnoreAnnotation( "ORM\MappedSuperclass" )
 * @Doctrine\Common\Annotations\Annotation\IgnoreAnnotation("ORM\Column")
 */
#[ORM\Entity]
#[ORM\Table(name: "VSAPP_Applications")]
class Application extends BaseApplication
{
    /** @var ReadingRoomApplication */
    #[ORM\OneToOne(targetEntity: ReadingRoomApplication::class, mappedBy: "application")]
    private $readingRoomApplication;
    
    public function getReadingRoomApplication()
    {
        return $this->readingRoomApplication;
    }
    
    public function setReadingRoomApplication($readingRoomApplication)
    {
        $this->readingRoomApplication  = $readingRoomApplication;
        
        return $this;
    }
}
