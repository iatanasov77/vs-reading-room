<?php namespace App\Controller\AdminPanel\Traits;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

trait FilterFormTrait
{
    protected function getFilterFormExtended( $filterCategory, $filterGenre, $filterAuthor, $request )
    {
        $categoryClass  = $this->getParameter( 'vs_catalog.model.product_category.class' );
        $genreClass     = $this->getParameter( 'vs_reading_room.model.book_genre.class' );
        $authorClass    = $this->getParameter( 'vs_reading_room.model.book_author.class' );
        
        $filterForm     = $this->createFormBuilder()
                            ->add( 'filterByCategory', EntityType::class, [
                                'class'                 => $categoryClass,
                                'choice_label'          => function ( $category ) use ( $request )
                                {
                                        return $category->getNameTranslated( $request->getLocale() );
                                },
                                'required'              => true,
                                'label'                 => 'vs_application.form.filter_by_category',
                                'placeholder'           => 'vs_application.form.category_placeholder',
                                'translation_domain'    => 'VSApplicationBundle',
                                'data'                  => $filterCategory ?
                                                            $this->getFilterRepository()->find( $filterCategory ) :
                                                            null,
                                ])
                                ->add( 'filterByGenre', EntityType::class, [
                                    'class'                 => $genreClass,
                                    'choice_label'          => function ( $genre ) use ( $request )
                                    {
                                            return $genre->getNameTranslated( $request->getLocale() );
                                    },
                                    'required'              => true,
                                    'label'                 => 'reading_room.form.filter_by_genre',
                                    'placeholder'           => 'reading_room.form.filter_by_genre_placeholder',
                                    'translation_domain'    => 'ReadingRoom',
                                    'data'                  => $filterGenre ?
                                                                $this->get( 'vs_reading_room.repository.book_genre' )->find( $filterGenre ) :
                                                                null,
                                ])
                                ->add( 'filterByAuthor', EntityType::class, [
                                    'class'                 => $authorClass,
                                    'choice_label'          => function ( $author ) use ( $request )
                                    {
                                        return $author->getName();
                                    },
                                    'required'              => true,
                                    'label'                 => 'reading_room.form.filter_by_author',
                                    'placeholder'           => 'reading_room.form.filter_by_author_placeholder',
                                    'translation_domain'    => 'ReadingRoom',
                                    'data'                  => $filterAuthor ?
                                                                $this->get( 'vs_reading_room.repository.book_author' )->find( $filterAuthor ) :
                                                                null,
                                ])
                                ->getForm();
        
        return $filterForm;
    }
    
    abstract protected function getFilterRepository();
}
