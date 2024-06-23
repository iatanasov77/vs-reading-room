<?php namespace App\Form;

use Vankosoft\ApplicationBundle\Form\AbstractForm;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Sylius\Component\Resource\Repository\RepositoryInterface;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Vankosoft\CatalogBundle\Form\ProductForm;
use Vankosoft\PaymentBundle\Model\Product;
use Vankosoft\PaymentBundle\Model\Interfaces\ProductInterface;

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

        $builder
            ->add( 'genres', EntityType::class, [
                'label'                 => 'vs_vvp.form.actor.genres',
                'translation_domain'    => 'VanzVideoPlayer',
                'multiple'              => true,    // Multiple Can be Changed in Template
                'required'              => false,
                //'mapped'                => false,
                'placeholder'           => 'vs_vvp.form.actor.genres_placeholder',
                
                'class'                 => $this->bookGenreClass,
                'choice_label'          => 'name'
            ])
            
            ->add( 'authors', EntityType::class, [
                'label'                 => 'vs_vvp.form.book.authors',
                'translation_domain'    => 'VanzVideoPlayer',
                'multiple'              => true,    // Multiple Can be Changed in Template
                'required'              => false,
                //'mapped'                => false,
                'placeholder'           => 'vs_vvp.form.book.authors_placeholder',
                
                'class'                 => $this->bookAuthorClass,
                'choice_label'          => 'name'
            ])
        ;
    }
}

