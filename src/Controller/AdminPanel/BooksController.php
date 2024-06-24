<?php namespace App\Controller\AdminPanel;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use Vankosoft\CatalogBundle\Controller\ProductController;

class BooksController extends ProductController
{
    protected function prepareEntity( &$entity, &$form, Request $request )
    {
        $categories = new ArrayCollection();
        $pcr        = $this->get( 'vs_catalog.repository.product_category' );
        
        $formLocale = $request->request->get( 'locale' );
        $formPost   = $request->request->all( 'book_form' );
        $formTaxon  = isset( $formPost['category_taxon'] ) ? $formPost['category_taxon'] : null;
        
        if ( $formLocale ) {
            $entity->setTranslatableLocale( $formLocale );
        }
        
        if ( $formTaxon ) {
            foreach ( $formTaxon as $taxonId ) {
                $category       = $pcr->findOneBy( ['taxon' => $taxonId] );
                if ( $category ) {
                    $categories[]   = $category;
                    $entity->addCategory( $category );
                }
            }
            
            foreach ( $entity->getCategories() as $cat ) {
                if ( ! $categories->contains( $cat ) ) {
                    $entity->removeCategory( $cat );
                }
            }
        }
        
        $formFiles  = $request->files->get( 'book_form' );
        
        $pictures   = $form['pictures']->getData();
        if ( ! empty( $formFiles['pictures'] ) ) {
            foreach ( $formFiles['pictures'] as $pictureId => $picture ) {
                if ( ! $picture['picture'] ) {
                    continue;
                }
                
                $this->addProductPicture( $entity, $pictures[$pictureId], $picture['picture'], $formPost['pictures'][$pictureId]["code"] );
            }
        }
        
        $files      = $form['files']->getData();
        if ( ! empty( $formFiles['files'] ) ) {
            foreach ( $formFiles['files'] as $fileId => $file ) {
                if ( ! $file['file'] ) {
                    continue;
                }
                
                $this->addProductFile( $entity, $files[$fileId], $file['file'], $formPost['files'][$fileId]["code"] );
            }
        }
        
        /** WORKAROUND */
        foreach ( $entity->getPictures() as $pic ) {
            if ( empty( $pic->getPath() ) ) {
                $entity->removePicture( $pic );
            }
        }
        foreach ( $entity->getFiles() as $file ) {
            if ( empty( $file->getPath() ) ) {
                $entity->removeFile( $file );
            }
        }
    }
}