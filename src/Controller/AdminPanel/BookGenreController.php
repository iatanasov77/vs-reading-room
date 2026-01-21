<?php namespace App\Controller\AdminPanel;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Vankosoft\ApplicationBundle\Controller\AbstractCrudController;
use Vankosoft\ApplicationBundle\Controller\Traits\TaxonomyHelperTrait;

use App\Entity\BookGenre;

class BookGenreController extends AbstractCrudController
{
    use TaxonomyHelperTrait;
    
    protected function customData( Request $request, $entity = NULL ): array
    {
        $taxonomy   = $this->getTaxonomy( 'vs_reading_room.book-genres.taxonomy_code' );
        
        $translations   = $this->classInfo['action'] == 'indexAction' ? $this->getTranslations() : [];
        if ( $entity && $entity->getTaxon() ) {
            $entity->getTaxon()->setCurrentLocale( $request->getLocale() );
        }
        
        return [
            'taxonomyId'    => $taxonomy->getId(),
            'translations'  => $translations,
            'items'         => $this->getRepository()->findAll(),
        ];
    }
    
    protected function prepareEntity( &$entity, &$form, Request $request ): void
    {
        $translatableLocale     = $form['currentLocale']->getData();
        $genreName           = $form['name']->getData();
        //$parentCategory         = $form['parent']->getData();
        
        /*
         * Create Category
         */
        if ( ! $translatableLocale ) {
            $translatableLocale = $request->getLocale();
        }
        $this->createBookGenre( $entity, $genreName, $translatableLocale );
    }
    
    private function createBookGenre(
        BookGenre &$bookGenre,
        string $name,
        string $locale
    ): void {
        if ( $bookGenre->getTaxon() ) {
            $bookGenre->getTaxon()->setCurrentLocale( $locale );
            $bookGenre->getTaxon()->setName( $name );
            if ( $parentCategory ) {
                $bookGenre->getTaxon()->setParent( $parentCategory->getTaxon() );
            }
        } else {
            /*
             * @WORKAROUND Create Taxon If not exists
             */
            $taxonomy   = $this->getTaxonomy( 'vs_reading_room.book-genres.taxonomy_code' );
            
            $newTaxon   = $this->createTaxon(
                $name,
                $locale,
                null,
                $taxonomy->getId()
            );
            
            $bookGenre->setTaxon( $newTaxon );
        }
    }
}
