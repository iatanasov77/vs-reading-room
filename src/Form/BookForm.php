<?php namespace App\Form;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Form\FormBuilderInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use daddl3\SymfonyCKEditor5WebpackViteBundle\Form\Ckeditor5TextareaType;

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
            ->add( 'description', Ckeditor5TextareaType::class, [
                'label'                 => 'vs_payment.form.description',
                'translation_domain'    => 'VSPaymentBundle',
                'required'              => false,
                
                'attr' => [
                    'data-ckeditor5-config' => 'default'
                ],
            ])
            
            ->add( 'files', CollectionType::class, [
                'entry_type'   => Type\ProductFileType::class,
                'allow_add'    => true,
                'allow_delete' => true,
                'prototype'    => true,
                'by_reference' => false
            ])
        
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

