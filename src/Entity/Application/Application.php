<?php namespace App\Entity\Application;

use Doctrine\ORM\Mapping as ORM;
use Vankosoft\ApplicationBundle\Model\Application as BaseApplication;
use App\Entity\ReadingRoomApplication;

#[ORM\Entity]
#[ORM\Table(name: "VSAPP_Applications")]
class Application extends BaseApplication
{
    /** @var VideoPlatformApplication */
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
