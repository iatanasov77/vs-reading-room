<?php namespace App\Form;

use Vankosoft\UsersBundle\Form\ProfileFormType as BaseProfileFormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ProfileFormType extends BaseProfileFormType
{
    public function buildForm( FormBuilderInterface $builder, array $options ): void
    {
        parent::buildForm( $builder, $options );
        
        $builder
            ->add( 'autoBookmark', CheckboxType::class, [
                'label' => 'reading_room.form.auto_bookmark',
                'translation_domain'    => 'ReadingRoom',
            ])
        ;
    }
}
