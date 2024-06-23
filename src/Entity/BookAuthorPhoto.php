<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vankosoft\CmsBundle\Model\File;

#[ORM\Entity]
#[ORM\Table(name: "VSCAT_BookAuthors_Photos")]
class BookAuthorPhoto extends File
{
    /** @var BookAuthor */
    #[ORM\ManyToOne(targetEntity: BookAuthor::class, inversedBy: "photos", cascade: ["persist", "remove"])]
    #[ORM\JoinColumn(name: "owner_id", referencedColumnName: "id", nullable: true, onDelete: "CASCADE")]
    protected $owner;
    
    public function getAuthor()
    {
        return $this->owner;
    }
    
    public function setAuthor( BookAuthor $author ): self
    {
        $this->setOwner( $author );
        
        return $this;
    }
}
