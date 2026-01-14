<?php namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\RequestStack;
use Sylius\Component\Resource\Repository\RepositoryInterface;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

use Vankosoft\ApplicationBundle\Component\Exception\FormInitializationException;
use Vankosoft\CatalogBundle\Component\Product;

class ProductFileType extends AbstractType
{
    /** @var string */
    private $dataClass;
    
    /** @var RepositoryInterface */
    protected $localesRepository;
    
    /** @var RequestStack */
    protected $requestStack;
    
    public function __construct(
        string $dataClass,
        RequestStack $requestStack,
        RepositoryInterface $localesRepository
    ) {
        $this->dataClass            = $dataClass;
        $this->requestStack         = $requestStack;
        $this->localesRepository    = $localesRepository;
    }
    
    public function buildForm( FormBuilderInterface $builder, array $options ): void
    {
        $currentLocale  = $this->requestStack->getCurrentRequest()->getLocale();
        //var_dump( $this->fillLocaleChoices() ); die;
        
        $builder
        
            ->add( 'code', ChoiceType::class, [
                'label'                 => 'vs_catalog.form.product.file_code',
                'placeholder'           => 'vs_catalog.form.product.file_code_placeholder',
                'translation_domain'    => 'VSCatalogBundle',
                'choices'               => \array_flip( Product::PRODUCT_FILE_TYPES ),
                'required'              => false,
                'mapped'                => false,
            ])
            
            ->add( 'locale', ChoiceType::class, [
                'label'                 => 'vs_cms.form.locale',
                'placeholder'           => false,
                'translation_domain'    => 'VSCmsBundle',
                'choices'               => \array_flip( $this->fillLocaleChoices() ),
                'data'                  => $currentLocale,
                'required'              => false,
                'mapped'                => false,
            ])
            
            ->add( 'file', FileType::class, [
                'mapped'                => false,
                'required'              => false,
                
                'label'                 => 'vs_application.form.file',
                'translation_domain'    => 'VSApplicationBundle',
                
                'constraints' => [
                    new File([
                        'maxSize' => '100m',
//                         'mimeTypes' => [
//                             'image/gif',
//                             'image/jpeg',
//                             'image/png',
//                             'image/svg+xml',
//                         ],
//                         'mimeTypesMessage' => 'vs_application.form.file_invalid',
                    ])
                ],
            ])
        ;
    }
    
    public function configureOptions( OptionsResolver $resolver ): void
    {
        $resolver->setDefaults(array(
            'data_class' => $this->dataClass
        ));
    }
    
    public function getName()
    {
        return 'FormFieldsetField';
    }
    
    protected function fillLocaleChoices(): array
    {
        if ( ! $this->localesRepository && ! $this->requestStack ) {
            throw new FormInitializationException( 'To Can Fill Locale Choices Needs Locales Repository and Request Stack.' );
        }
        
        $results = $this->localesRepository->findAll();
        
        $locales = [];
        foreach( $results as $le ) {
            if ( ! $le->isActive() ) {
                continue;
            }
            
            $locales[$le->getCode()] = $le->getTitle();
        }
        
        return $locales;
    }
}
