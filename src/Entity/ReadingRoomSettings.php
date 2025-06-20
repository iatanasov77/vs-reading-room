<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Resource\Model\ResourceInterface;
use App\Entity\Catalog\AssociationType;

/**
 * @Doctrine\Common\Annotations\Annotation\IgnoreAnnotation( "ORM\MappedSuperclass" )
 * @Doctrine\Common\Annotations\Annotation\IgnoreAnnotation("ORM\Column")
 */
#[ORM\Entity]
#[ORM\Table(name: "VRR_ReadingRoomSettings")]
class ReadingRoomSettings implements ResourceInterface
{
    /** @var int */
    #[ORM\Id, ORM\Column(type: "integer"), ORM\GeneratedValue(strategy: "IDENTITY")]
    private $id;
    
    /** @var ReadingRoomApplication */
    #[ORM\OneToMany(targetEntity: "ReadingRoomApplication", mappedBy: "settings")]
    private $readingRoomApplication;
    
    /** @var AssociationType */
    #[ORM\ManyToOne(targetEntity: AssociationType::class)]
    #[ORM\JoinColumn(name: "book_suggestions_association_type_id", referencedColumnName: "id", nullable: true)]
    private $bookSuggestionsAssociationType;
    
    /** @var string */
    #[ORM\Column(name: "settings_key", type: "string", length: 32)]
    private $settingsKey;
    
    /** @var bool */
    #[ORM\Column(name: "show_price", type: "boolean", options: ["default" => 0, ])]
    private $showPrice = false;
    
    /** @var bool */
    #[ORM\Column(name: "open_file", type: "boolean", options: ["default" => 0, "comment" => "Setting for 'ng2-pdfjs-viewer' Component"])]
    private $openFile = false;
    
    /** @var bool */
    #[ORM\Column(name: "view_bookmark", type: "boolean", options: ["default" => 0, "comment" => "Setting for 'ng2-pdfjs-viewer' Component"])]
    private $viewBookmark = false;
    
    /** @var bool */
    #[ORM\Column(name: "download", type: "boolean", options: ["default" => 0, "comment" => "Setting for 'ng2-pdfjs-viewer' Component"])]
    private $download = false;
    
    /** @var bool */
    #[ORM\Column(name: "print", type: "boolean", options: ["default" => 0, "comment" => "Setting for 'ng2-pdfjs-viewer' Component"])]
    private $print = false;
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getReadingRoomApplication()
    {
        return $this->readingRoomApplication;
    }
    
    public function setReadingRoomApplication($readingRoomApplication)
    {
        $this->readingRoomApplication  = $readingRoomApplication;
        
        return $this;
    }
    
    public function getBookSuggestionsAssociationType()
    {
        return $this->bookSuggestionsAssociationType;
    }
    
    public function setBookSuggestionsAssociationType($bookSuggestionsAssociationType)
    {
        $this->bookSuggestionsAssociationType  = $bookSuggestionsAssociationType;
        
        return $this;
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
    
    public function getShowPrice()
    {
        return $this->showPrice;
    }
    
    public function setShowPrice( $showPrice )
    {
        $this->showPrice = $showPrice;
        
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