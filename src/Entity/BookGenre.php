<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Resource\Model\ResourceInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Vankosoft\ApplicationBundle\Model\Interfaces\TaxonDescendentInterface;
use Vankosoft\ApplicationBundle\Model\Traits\TaxonDescendentEntity;
use App\Entity\Catalog\Product;

#[ORM\Entity]
#[ORM\Table(name: "VSCAT_BookGenres")]
class BookGenre implements ResourceInterface, TaxonDescendentInterface
{
    use TaxonDescendentEntity;
    
    /** @var int */
    #[ORM\Id, ORM\Column(type: "integer"), ORM\GeneratedValue(strategy: "IDENTITY")]
    private $id;
    
    /** @var Collection|Product[] */
    #[ORM\ManyToMany(targetEntity: Product::class, mappedBy: "genres")]
    #[ORM\OrderBy(["updatedAt" => "DESC"])]
    private $books;
    
    /** @var Collection|BookAuthor[] */
    #[ORM\ManyToMany(targetEntity: "BookAuthor", mappedBy: "genres")]
    #[ORM\OrderBy(["updatedAt" => "DESC"])]
    private $authors;
    
    public function __construct()
    {
        $this->books    = new ArrayCollection();
        $this->authors  = new ArrayCollection();
    }
    
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @return Collection|Product[]
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }
    
    public function addBook( Product $book ): self
    {
        if ( ! $this->books->contains( $book ) ) {
            $this->books[] = $book;
            $book->addGenre( $this );
        }
        
        return $this;
    }
    
    public function removeBook( Product $book ): self
    {
        if ( $this->books->contains( $book ) ) {
            $this->books->removeElement( $book );
            $book->removeGenre( $this );
        }
        
        return $this;
    }
    
    /**
     * @return Collection|Author[]
     */
    public function getAuthors(): Collection
    {
        return $this->authors;
    }
    
    public function addAuthor( BookAuthor $author ): self
    {
        if ( ! $this->authors->contains( $author ) ) {
            $this->authors[] = $author;
            $author->addGenre( $this );
        }
        
        return $this;
    }
    
    public function removeAuthor( BookAuthor $author ): self
    {
        if ( $this->authors->contains( $author ) ) {
            $this->authors->removeElement( $author );
            $author->removeGenre( $this );
        }
        
        return $this;
    }
    
    public function __toString()
    {
        return $this->getNameTranslated( 'en_US' );
        //return $this->taxon ? $this->taxon->getName() : '';
    }
}
