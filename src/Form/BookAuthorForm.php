<?php namespace App\Form;

use Vankosoft\ApplicationBundle\Form\AbstractForm;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use daddl3\SymfonyCKEditor5WebpackViteBundle\Form\Ckeditor5TextareaType;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

use App\Form\Type\BookAuthorPhotoType;
use App\Entity\BookAuthor;
use Vankosoft\CmsBundle\Form\Traits\FosCKEditor4Config;

class BookAuthorForm extends AbstractForm
{
    use FosCKEditor4Config;
    
    /** @var string */
    private $bookClass;
    
    /** @var string */
    private $genreClass;
    
    /** @var string */
    private $useCkEditor;
    
    /** @var string */
    private $ckeditor5Editor;
    
    public function __construct(
        string $dataClass,
        RepositoryInterface $localesRepository,
        RequestStack $requestStack,
        string $bookClass,
        string $genreClass,
        
        string $useCkEditor,
        string $ckeditor5Editor
    ) {
        parent::__construct( $dataClass );
        
        $this->localesRepository    = $localesRepository;
        $this->requestStack         = $requestStack;
        $this->bookClass            = $bookClass;
        $this->genreClass           = $genreClass;
        
        $this->useCkEditor          = $useCkEditor;
        $this->ckeditor5Editor      = $ckeditor5Editor;
    }
    
    public function buildForm( FormBuilderInterface $builder, array $options ): void
    {
        parent::buildForm( $builder, $options );
        
        $entity         = $builder->getData();
        $currentLocale  = $entity->getTranslatableLocale() ?: $this->requestStack->getCurrentRequest()->getLocale();
        
        $builder
            ->add( 'authorBooks', HiddenType::class, [
                'mapped'    => false,
                'data'      => \json_encode( $entity->getBooks()->getKeys() )
            ])
            
            ->add( 'authorGenres', HiddenType::class, [
                'mapped'    => false,
                'data'      => \json_encode( $entity->getGenres()->getKeys() )
            ])
        
            ->add( 'locale', ChoiceType::class, [
                'label'                 => 'reading_room.form.locale',
                'translation_domain'    => 'ReadingRoom',
                'choices'               => \array_flip( $this->fillLocaleChoices() ),
                'data'                  => $currentLocale,
                'mapped'                => false,
            ])
            
            ->add( 'name', TextType::class, [
                'label'                 => 'reading_room.form.author.name',
                'translation_domain'    => 'ReadingRoom',
            ])
            
            ->add( 'photos', CollectionType::class, [
                'entry_type'   => BookAuthorPhotoType::class,
                'allow_add'    => true,
                'allow_delete' => true,
                'prototype'    => true,
                'by_reference' => false
            ])
            
            ->add( 'books', EntityType::class, [
                'label'                 => 'reading_room.form.author.books',
                'translation_domain'    => 'ReadingRoom',
                'multiple'              => true,    // Multiple Can be Changed in Template
                'required'              => false,
                //'mapped'                => false,
                'placeholder'           => 'reading_room.form.author.books_placeholder',
                
                'class'                 => $this->bookClass,
                'choice_label'          => 'name'
            ])
            
            ->add( 'genres', EntityType::class, [
                'label'                 => 'reading_room.form.author.genres',
                'translation_domain'    => 'ReadingRoom',
                'multiple'              => true,    // Multiple Can be Changed in Template
                'required'              => false,
                //'mapped'                => false,
                'placeholder'           => 'reading_room.form.author.genres_placeholder',
                
                'class'                 => $this->genreClass,
                'choice_label'          => 'name'
            ])
        ;
        
        if ( $this->useCkEditor == '5' ) {
            $builder->add( 'description', Ckeditor5TextareaType::class, [
                'label'                 => 'reading_room.form.author.description',
                'translation_domain'    => 'ReadingRoom',
                'required'              => false,
                'attr' => [
                    'data-ckeditor5-config' => 'devpage'
                ],
            ]);
        } else {
            $builder->add( 'description', CKEditorType::class, [
                'label'                 => 'reading_room.form.author.description',
                'translation_domain'    => 'ReadingRoom',
                'config'                => $this->ckEditorConfig( $options ),
                'required'              => false,
            ]);
        }
    }
    
    public function configureOptions( OptionsResolver $resolver ): void
    {
        parent::configureOptions( $resolver );
        
        $resolver
            ->setDefaults([
                'data_class'        => BookAuthor::class,
                'csrf_protection'   => false,
            ])
        ;
            
        $this->configureCkEditorOptions( $resolver );
    }
    
    public function getName()
    {
        return 'vs_reading_room.book_author';
    }
}