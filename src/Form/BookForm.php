<?php namespace App\Form;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Form\FormBuilderInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Vankosoft\CatalogBundle\Form\ProductForm;

class BookForm extends ProductForm
{
    /** @var string */
    private $bookGenreClass;
    
    /** @var string */
    private $bookAuthorClass;
    
    public function __construct(
        string $dataClass,
        RequestStack $requestStack,
        RepositoryInterface $localesRepository,
        string $categoryClass,
        string $currencyClass,
        string $bookGenreClass,
        string $bookAuthorClass
    ) {
        parent::__construct( $dataClass, $requestStack, $localesRepository, $categoryClass, $currencyClass );
        
        $this->bookGenreClass   = $bookGenreClass;
        $this->bookAuthorClass  = $bookAuthorClass;
    }
    
    public function buildForm( FormBuilderInterface $builder, array $options ): void
    {
        parent::buildForm( $builder, $options );
        
//         $builder->remove( 'price' );
//         $builder->remove( 'currency' );

        $entity = $builder->getData();
        $builder
            ->add( 'bookAuthors', HiddenType::class, [
                'mapped'    => false,
                'data'      => \json_encode( $entity->getAuthors()->getKeys() )
            ])
            
            ->add( 'bookGenres', HiddenType::class, [
                'mapped'    => false,
                'data'      => \json_encode( $entity->getGenres()->getKeys() )
            ])
            
            ->add( 'genres', EntityType::class, [
                'label'                 => 'reading_room.form.product.genres',
                'translation_domain'    => 'ReadingRoom',
                'multiple'              => true,    // Multiple Can be Changed in Template
                'required'              => false,
                //'mapped'                => false,
                'placeholder'           => 'reading_room.form.product.genres_placeholder',
                
                'class'                 => $this->bookGenreClass,
                'choice_label'          => 'name'
            ])
            
            ->add( 'authors', EntityType::class, [
                'label'                 => 'reading_room.form.product.authors',
                'translation_domain'    => 'ReadingRoom',
                'multiple'              => true,    // Multiple Can be Changed in Template
                'required'              => false,
                //'mapped'                => false,
                'placeholder'           => 'reading_room.form.product.authors_placeholder',
                
                'class'                 => $this->bookAuthorClass,
                'choice_label'          => 'name'
            ])
        ;
    }
}

