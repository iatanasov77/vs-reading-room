<?php namespace App\Entity\Catalog;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Vankosoft\CatalogBundle\Model\Product as BaseProduct;
use Vankosoft\CmsBundle\Model\Interfaces\DocumentInterface;
use App\Entity\Cms\Document;
use App\Entity\UserManagement\User;
use App\Entity\BookGenre;
use App\Entity\BookAuthor;

/**
 * @Doctrine\Common\Annotations\Annotation\IgnoreAnnotation( "ORM\MappedSuperclass" )
 * @Doctrine\Common\Annotations\Annotation\IgnoreAnnotation("ORM\Column")
 */
#[ORM\Entity]
#[ORM\Table(name: "VSCAT_Products")]
class Product extends BaseProduct
{
    /** @var User */
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "submittedBooks")]
    private $user;
    
    /** @var DocumentInterface */
    #[ORM\OneToOne(targetEntity: Document::class, cascade: ["all"], orphanRemoval: true)]
    #[ORM\JoinColumn(name: "document_id", referencedColumnName: "id", nullable: true)]
    private $document;
    
    /** @var Collection|BookGenre[] */
    #[ORM\ManyToMany(targetEntity: BookGenre::class, inversedBy: "books", indexBy: "id")]
    #[ORM\JoinTable(name: "VSCAT_Books_Genres")]
    #[ORM\JoinColumn(name: "book_id", referencedColumnName: "id")]
    #[ORM\InverseJoinColumn(name: "genre_id", referencedColumnName: "id")]
    private $genres;
    
    /** @var Collection|Author[] */
    #[ORM\ManyToMany(targetEntity: BookAuthor::class, inversedBy: "books", indexBy: "id")]
    #[ORM\JoinTable(name: "VSCAT_Books_Authors")]
    #[ORM\JoinColumn(name: "book_id", referencedColumnName: "id")]
    #[ORM\InverseJoinColumn(name: "author_id", referencedColumnName: "id")]
    private $authors;
    
    public function __construct()
    {
        parent::__construct();
        
        $this->genres   = new ArrayCollection();
        $this->authors  = new ArrayCollection();
    }
    
    public function getUser()
    {
        return $this->user;
    }
    
    public function setUser($user)
    {
        $this->user = $user;
        
        return $this;
    }
    
    public function getDocument(): ?DocumentInterface
    {
        return $this->document;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setDocument( ?DocumentInterface $document ): self
    {
        $this->document = $document;
        
        return $this;
    }
    public function getGenres()
    {
        return $this->genres;
    }
    
    public function addGenre( BookGenre $genre ): self
    {
        if ( ! $this->genres->contains( $genre ) ) {
            $this->genres[] = $genre;
        }
        
        return $this;
    }
    
    public function removeGenre( BookGenre $genre ): self
    {
        if ( $this->genres->contains( $genre ) ) {
            $this->genres->removeElement( $genre );
        }
        
        return $this;
    }
    
    public function getAuthors()
    {
        return $this->authors;
    }
    
    public function addAuthor( BookAuthor $author ): self
    {
        if ( ! $this->authors->contains( $author ) ) {
            $this->authors[] = $author;
        }
        
        return $this;
    }
    
    public function removeAuthor( BookAuthor $author ): self
    {
        if ( $this->authors->contains( $author ) ) {
            $this->authors->removeElement( $author );
        }
        
        return $this;
    }
}
