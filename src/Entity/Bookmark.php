<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Resource\Model\ResourceInterface;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @Doctrine\Common\Annotations\Annotation\IgnoreAnnotation( "ORM\MappedSuperclass" )
 * @Doctrine\Common\Annotations\Annotation\IgnoreAnnotation("ORM\Column")
 */
#[ORM\Entity]
#[ORM\Table(name: "VRR_Bookmarks")]
class Bookmark implements ResourceInterface
{
    use TimestampableEntity;
    
    /** @var int */
    #[ORM\Id, ORM\Column(type: "integer"), ORM\GeneratedValue(strategy: "IDENTITY")]
    private $id;
    
    /** @var int */
    #[ORM\Column(type: "integer")]
    private $bookId;
    
    /** @var string */
    #[ORM\Column(type: "string", length: 6)]
    private $locale;
    
    /** @var int */
    #[ORM\Column(type: "integer")]
    private $userId;
    
    /** @var int */
    #[ORM\Column(type: "integer")]
    private $page;
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getBookId(): ?int
    {
        return $this->bookId;
    }
    
    public function setBookId($bookId): self
    {
        $this->bookId = $bookId;
        
        return $this;
    }
    
    public function getLocale(): ?string
    {
        return $this->locale;
    }
    
    public function setLocale($locale)
    {
        $this->locale   = $locale;
        
        return $this;
    }
    
    public function getUserId(): ?int
    {
        return $this->userId;
    }
    
    public function setUserId($userId): self
    {
        $this->userId = $userId;
        
        return $this;
    }
    
    public function getPage(): ?int
    {
        return $this->page;
    }
    
    public function setPage($page): self
    {
        $this->page  = $page;
        
        return $this;
    }
}
