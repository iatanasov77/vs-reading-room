<?php namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

use Vankosoft\CatalogBundle\Component\Product;

class ProductFileType extends AbstractType
{
    /** @var string */
    private $dataClass;
    
    public function __construct(
        string $dataClass
    ) {
        $this->dataClass    = $dataClass;
    }
    
    public function buildForm( FormBuilderInterface $builder, array $options ): void
    {
        $builder
        
            ->add( 'code', ChoiceType::class, [
                'label'                 => 'vs_catalog.form.product.file_code',
                'placeholder'           => 'vs_catalog.form.product.file_code_placeholder',
                'translation_domain'    => 'VSCatalogBundle',
                'choices'               => \array_flip( Product::PRODUCT_FILE_TYPES ),
                'required'              => false,
            ])
            
            ->add( 'file', FileType::class, [
                'mapped'                => false,
                'required'              => false,
                
                'label'                 => 'vs_application.form.file',
                'translation_domain'    => 'VSApplicationBundle',
                
                'constraints' => [
                    new File([
                        'maxSize' => '10000k',
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
}
