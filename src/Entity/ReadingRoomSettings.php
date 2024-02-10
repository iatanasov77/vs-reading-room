<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="VRR_ReadingRoomSettings")
 */
class ReadingRoomSettings implements ResourceInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    
    /**
     * @ORM\Column(name="settings_key", type="string", length=32, nullable=false)
     */
    private $settingsKey;
    
    /**
     * Setting for 'ng2-pdfjs-viewer' Component
     * 
     * @var bool
     *
     * @ORM\Column(name="open_file", type="boolean", options={"default":"0", "comment":"Setting for 'ng2-pdfjs-viewer' Component"})
     */
    private $openFile = false;
    
    /**
     * Setting for 'ng2-pdfjs-viewer' Component
     *
     * @var bool
     *
     * @ORM\Column(name="view_bookmark", type="boolean", options={"default":"0", "comment":"Setting for 'ng2-pdfjs-viewer' Component"})
     */
    private $viewBookmark = false;
    
    /**
     * Setting for 'ng2-pdfjs-viewer' Component
     *
     * @var bool
     *
     * @ORM\Column(name="download", type="boolean", options={"default":"0", "comment":"Setting for 'ng2-pdfjs-viewer' Component"})
     */
    private $download = false;
    
    /**
     * Setting for 'ng2-pdfjs-viewer' Component
     *
     * @var bool
     *
     * @ORM\Column(name="print", type="boolean", options={"default":"0", "comment":"Setting for 'ng2-pdfjs-viewer' Component"})
     */
    private $print = false;
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getSettingsKey()
    {
        return $this->settingsKey;
    }
    
    public function setSettingsKey($settingsKey)
    {
        $this->settingsKey  = $settingsKey;
        
        return $this;
    }
    
    public function getOpenFile()
    {
        return $this->openFile;
    }
    
    public function setOpenFile( $openFile )
    {
        $this->openFile = $openFile;
        
        return $this;
    }
    
    public function getViewBookmark()
    {
        return $this->viewBookmark;
    }
    
    public function setViewBookmark( $viewBookmark )
    {
        $this->viewBookmark = $viewBookmark;
        
        return $this;
    }
    
    public function getDownload()
    {
        return $this->download;
    }
    
    public function setDownload( $download )
    {
        $this->download = $download;
        
        return $this;
    }
    
    public function getPrint()
    {
        return $this->print;
    }
    
    public function setPrint( $print )
    {
        $this->print = $print;
        
        return $this;
    }
}