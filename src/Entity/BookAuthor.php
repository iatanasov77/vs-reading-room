<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Gedmo\Mapping\Annotation as Gedmo;
use Sylius\Component\Resource\Model\ResourceInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Vankosoft\ApplicationBundle\Model\Traits\TranslatableTrait;
use Vankosoft\ApplicationBundle\Model\Interfaces\TranslatableInterface;
use App\Entity\Application\Translation;
use App\Entity\Catalog\Product;

/**
 * @Doctrine\Common\Annotations\Annotation\IgnoreAnnotation( "ORM\MappedSuperclass" )
 * @Doctrine\Common\Annotations\Annotation\IgnoreAnnotation("ORM\Column")
 */
#[ORM\Entity]
#[ORM\Table(name: "VSCAT_BookAuthors")]
#[Gedmo\TranslationEntity(class: Translation::class)]
class BookAuthor implements ResourceInterface, TranslatableInterface
{
    use TimestampableEntity;
    use TranslatableTrait;
    
    /** @var int */
    #[ORM\Id, ORM\Column(type: "integer"), ORM\GeneratedValue(strategy: "IDENTITY")]
    private $id;
    
    /** @var Collection|Product[] */
    #[ORM\ManyToMany(targetEntity: Product::class, mappedBy: "authors", indexBy: "id")]
    #[ORM\OrderBy(["createdAt" => "DESC"])]
    private $books;
    
    /** @var string */
    #[ORM\Column(type: "string", length: 255, unique: true)]
    #[Gedmo\Translatable]
    #[Gedmo\Slug(fields: ["name", "id"])]
    private $slug;
    
    /** @var string */
    #[ORM\Column(type: "string", length: 255)]
    #[Gedmo\Translatable]
    private $name;
    
    /** @var string */
    #[ORM\Column(type: "text", nullable: true)]
    #[Gedmo\Translatable]
    private $description;
    
    /** @var Collection|AuthorPhoto[] */
    #[ORM\OneToMany(targetEntity: "BookAuthorPhoto", mappedBy: "owner", indexBy: "id", cascade: ["persist", "remove"], orphanRemoval: true)]
    private $photos;
    
    /** @var Collection|BookGenre[] */
    #[ORM\ManyToMany(targetEntity: "BookGenre", inversedBy: "authors", indexBy: "id")]
    #[ORM\JoinTable(name: "VSCAT_Authors_Genres")]
    #[ORM\JoinColumn(name: "author_id", referencedColumnName: "id")]
    #[ORM\InverseJoinColumn(name: "genre_id", referencedColumnName: "id")]
    private $genres;
    
    /** {@inheritDoc} */
    #[Gedmo\Locale]
    protected $locale;
    
    public function __construct()
    {
        $this->genres   = new ArrayCollection();
        $this->books    = new ArrayCollection();
        $this->photos   = new ArrayCollection();
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @return Collection|Product[]
     */
    public function getBooks()
    {
        return $this->books;
    }
    
    public function addBook( Product $book ): self
    {
        if ( ! $this->books->contains( $book ) ) {
            $this->books[] = $book;
            $book->addAuthor( $this );
        }
        
        return $this;
    }
    
    public function removeBook( Product $book ): self
    {
        if ( $this->books->contains( $book ) ) {
            $this->books->removeElement( $book );
            $book->removeAuthor( $this );
        }
        
        return $this;
    }
    
    public function getSlug(): ?string
    {
        return $this->slug;
    }
    
    public function setSlug($slug): self
    {
        $this->slug = $slug;
        
        return $this;
    }
    
    public function getName(): ?string
    {
        return $this->name;
    }
    
    public function setName($name)
    {
        $this->name   = $name;
        
        return $this;
    }
    
    public function getDescription()
    {
        return $this->description;
    }
    
    public function setDescription($description)
    {
        $this->description  = $description;
        
        return $this;
    }
    
    public function getPhotos()
    {
        return $this->photos;
    }
    
    public function addPhoto( BookAuthorPhoto $photo ): self
    {
        if ( ! $this->photos->contains( $photo ) ) {
            $this->photos[] = $photo;
            $photo->setAuthor( $this );
        }
        
        return $this;
    }
    
    public function removePhoto( BookAuthorPhoto $photo ): self
    {
        if ( $this->photos->contains( $photo ) ) {
            $this->photos->removeElement( $photo );
            $photo->setAuthor( null );
        }
        
        return $this;
    }
    
    public function getPhoto( $photoId ):? AuthorPhoto
    {
        if ( ! isset( $this->photos[$photoId] ) ) {
            return null;
        }
        
        return $this->photos[$photoId];
    }
    
    /**
     * @return Collection|BookGenre[]
     */
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
    
    public function __toString()
    {
        return $this->name;
    }
}