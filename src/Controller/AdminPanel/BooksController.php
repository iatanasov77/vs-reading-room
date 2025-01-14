<?php namespace App\Controller\AdminPanel;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Doctrine\Common\Collections\ArrayCollection;
use Vankosoft\CatalogBundle\Controller\ProductController;
use Vankosoft\CatalogBundle\Model\Interfaces\ProductInterface;
use Vankosoft\CatalogBundle\Model\Interfaces\ProductFileInterface;
use Vankosoft\CatalogBundle\Component\Product;
use App\Controller\AdminPanel\Traits\FilterFormTrait;

class BooksController extends ProductController
{
    use FilterFormTrait;
    
    protected function customData( Request $request, $entity = null ): array
    {
        $translations   = $this->classInfo['action'] == 'indexAction' ? $this->getTranslations( false ) : [];
        
        $taxonomy   = $this->get( 'vs_application.repository.taxonomy' )->findByCode(
            $this->getParameter( 'vs_catalog.product_category.taxonomy_code' )
        );
        
        $selectedTaxonIds   = [];
        $associationsForm   = null;
        if ( $this->classInfo['action'] == 'updateAction' ) {
            foreach ( $entity->getCategories() as $cat ) {
                $selectedTaxonIds[] = $cat->getTaxon()->getId();
            }
            $associationsForm   = $this->getProductAssociationsForm( $entity );
        }
        
        $tagsContext    = $this->get( 'vs_application.repository.tags_whitelist_context' )->findByTaxonCode( 'catalog-products' );
        
        $filterCategory = $request->attributes->get( 'filterCategory' );
        $filterGenre    = $request->attributes->get( 'filterGenre' );
        $filterAuthor   = $request->attributes->get( 'filterAuthor' );
        $filterForm     = $this->getFilterFormExtended( $filterCategory, $filterGenre, $filterAuthor, $request );
        
        return [
            'items'             => $this->getRepository()->findAll(),
            'categories'        => $this->get( 'vs_catalog.repository.product_category' )->findAll(),
            'taxonomyId'        => $taxonomy ? $taxonomy->getId() : 0,
            'translations'      => $translations,
            'productTags'       => $tagsContext->getTagsArray(),
            'selectedTaxonIds'  => $selectedTaxonIds,
            'associationsForm'  => $associationsForm ? $associationsForm->createView() : null,
            'filterForm'        => $filterForm->createView(),
            'filterCategory'    => $filterCategory,
            'filterGenre'       => $filterGenre,
            'filterAuthor'      => $filterAuthor,
        ];
    }
    
    protected function prepareEntity( &$entity, &$form, Request $request )
    {
        $categories = new ArrayCollection();
        $pcr        = $this->get( 'vs_catalog.repository.product_category' );
        
        $formPost   = $request->request->all( 'book_form' );
        //$formLocale = $request->request->get( 'locale' );
        $formLocale = $formPost['locale'];
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
                
                $fileCode   = \sprintf( "%s_%s", $formPost['files'][$fileId]["code"], $formLocale );
                $this->addProductFile( $entity, $files[$fileId], $file['file'], $fileCode );
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
    
    protected function addProductFile( ProductInterface &$entity, ProductFileInterface &$productFile, File $file, string $code ): void
    {
        $productFile->setOriginalName( $file->getClientOriginalName() );
        
        $uploadedFile   = new UploadedFile( $file->getRealPath(), $file->getBasename() );
        $productFile->setFile( $uploadedFile );
        
        $this->get( 'vs_reading_room.book_uploader' )->upload( $productFile );
        $productFile->setFile( null ); // reset File Because: Serialization of 'Symfony\Component\HttpFoundation\File\UploadedFile' is not allowed
        
        if ( $code == Product::PRODUCT_FILE_TYPE_OTHER ) {
            $productFile->setCode( $code . '-' . \microtime() );
        } else {
            $productFile->setCode( $code );
        }
        
        $tranlatableLocale  = $entity->getTranslatableLocale();
        $productFile->setLocale( $tranlatableLocale );
        
        $entity->addFile( $productFile );
    }
}