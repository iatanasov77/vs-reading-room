<?php namespace App\Form;

use Vankosoft\ApplicationBundle\Form\AbstractForm;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use App\Entity\ReadingRoomApplication;
use App\Entity\ReadingRoomSettings;

class ReadingRoomApplicationForm extends AbstractForm
{
    public function buildForm( FormBuilderInterface $builder, array $options ): void
    {
        parent::buildForm( $builder, $options );
        
        $builder
            ->add( 'applicationCode', HiddenType::class, ['mapped'    => false] )
            
            ->add( 'settings', EntityType::class, [
                'label'                 => 'reading_room.form.reading_room_settings_label',
                'translation_domain'    => 'ReadingRoom',
                'placeholder'           => 'reading_room.form.reading_room_settings_placeholder',
                'class'                 => ReadingRoomSettings::class,
                'choice_label'          => 'settingsKey',
            ])
        ;
    }
    
    public function configureOptions( OptionsResolver $resolver ): void
    {
        parent::configureOptions( $resolver );
        
        $resolver
            ->setDefaults([
                'data_class'        => ReadingRoomApplication::class,
                'csrf_protection'   => false,
            ])
        ;
    }
    
    public function getName()
    {
        return 'vs_reading_room.reading_room_application';
    }
}