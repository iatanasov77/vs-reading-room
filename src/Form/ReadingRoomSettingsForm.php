<?php namespace App\Form;

use Vankosoft\ApplicationBundle\Form\AbstractForm;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use App\Entity\ReadingRoomSettings;
use App\Entity\Catalog\AssociationType;

class ReadingRoomSettingsForm extends AbstractForm
{
    public function buildForm( FormBuilderInterface $builder, array $options ): void
    {
        parent::buildForm( $builder, $options );
        
        $builder
            ->add( 'settingsKey', TextType::class, [
                'label'                 => 'reading_room.form.reading_room_settings.settings_key',
                'translation_domain'    => 'ReadingRoom',
            ])
            
            ->add( 'bookSuggestionsAssociationType', EntityType::class, [
                'required'              => false,
                'label'                 => 'reading_room.form.reading_room_settings.book_suggestions_strategy',
                'translation_domain'    => 'ReadingRoom',
                'class'                 => AssociationType::class,
                'choice_label'          => 'name',
                'placeholder'           => 'reading_room.form.reading_room_settings.book_suggestions_strategy_placeholder'
            ])
            
            ->add( 'showPrice', CheckboxType::class, [
                'required'              => false,
                
                'label'                 => 'reading_room.form.reading_room_settings.show_price',
                'translation_domain'    => 'ReadingRoom',
            ])
            
            ->add( 'enableRegistration', CheckboxType::class, [
                'required'              => false,
                
                'label'                 => 'reading_room.form.reading_room_settings.enable_registration',
                'translation_domain'    => 'ReadingRoom',
            ])
            
            ->add( 'openFile', CheckboxType::class, [
                'required'              => false,
                
                'label'                 => 'reading_room.form.reading_room_settings.open_file',
                'translation_domain'    => 'ReadingRoom',
            ])
            
            ->add( 'viewBookmark', CheckboxType::class, [
                'required'              => false,
                
                'label'                 => 'reading_room.form.reading_room_settings.view_bookmark',
                'translation_domain'    => 'ReadingRoom',
            ])
            
            ->add( 'download', CheckboxType::class, [
                'required'              => false,
                
                'label'                 => 'reading_room.form.reading_room_settings.download',
                'translation_domain'    => 'ReadingRoom',
            ])
            
            ->add( 'print', CheckboxType::class, [
                'required'              => false,
                
                'label'                 => 'reading_room.form.reading_room_settings.print',
                'translation_domain'    => 'ReadingRoom',
            ])
        ;
    }
    
    public function configureOptions( OptionsResolver $resolver ): void
    {
        parent::configureOptions( $resolver );
        
        $resolver
            ->setDefaults([
                'data_class'            => ReadingRoomSettings::class,
                'csrf_protection'       => false,
            ])
        ;
    }
    
    public function getName()
    {
        return 'vs_reading_room.reading_room_settings';
    }
}