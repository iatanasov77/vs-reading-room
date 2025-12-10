<?php namespace App\Controller\AdminPanel;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Vankosoft\ApplicationBundle\Controller\AbstractCrudController;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Doctrine\Common\Collections\ArrayCollection;

use Sylius\Component\Resource\ResourceActions;
use Vankosoft\ApplicationBundle\Component\Status;

use App\Entity\BookAuthor;
use App\Entity\BookAuthorPhoto;

class BookAuthorController extends AbstractCrudController
{
    public function deleteAction( Request $request ): Response
    {
        $configuration = $this->requestConfigurationFactory->create( $this->metadata, $request );
        $this->isGrantedOr403( $configuration, ResourceActions::DELETE );
        
        $resource   = $this->findOr404( $configuration );
        $em         = $this->get( 'doctrine' )->getManager();
        
        $this->removeAuthorPhotos( $resource );
        $em->remove( $resource );
        $em->flush();
        
        $redirectUrl    = $request->request->get( 'redirectUrl' );
        if ( $redirectUrl ) {
            return $this->redirect( $redirectUrl );
        }
        
        return new JsonResponse([
            'status'   => Status::STATUS_OK
        ]);
    }
    
    protected function customData( Request $request, $entity = NULL ): array
    {
        $translations   = $this->classInfo['action'] == 'indexAction' ? $this->getTranslations() : [];
        
        return [
            'translations'  => $translations,
            'dirPhotos'     => $this->getParameter( 'vs_application.filemanager_shared_media_gaufrette.app_pictures' ),
        ];
    }
    
    protected function prepareEntity( &$entity, &$form, Request $request )
    {
        $formPost   = $request->request->all( 'book_author_form' );
        $formLocale = $formPost['locale'];
        
        if ( $formLocale ) {
            $entity->setTranslatableLocale( $formLocale );
        }
        
        $this->booksPost( $entity, $formPost );
        
        $formFiles  = $request->files->get( 'book_author_form' );
        $photos = $form['photos']->getData();
        if ( ! empty( $formFiles['photos'] ) ) {
            foreach ( $formFiles['photos'] as $photoId => $photo ) {
                if ( ! $photo['photo'] ) {
                    continue;
                }
                $this->createPhoto( $entity, $photos[$photoId], $photo['photo'] );
            }
        }
    }
    
    private function getTranslations()
    {
        $translations   = [];
        $transRepo      = $this->get( 'vs_application.repository.translation' );
        
        foreach ( $this->getRepository()->findAll() as $author ) {
            $translations[$author->getId()] = \array_reverse( \array_keys( $transRepo->findTranslations( $author ) ) );
        }
        
        return $translations;
    }
    
    private function createPhoto( BookAuthor &$author, BookAuthorPhoto &$authorPhoto, File $file ): void
    {
        $authorPhoto->setOriginalName( $file->getClientOriginalName() );
        $authorPhoto->setAuthor( $author );
        
        $uploadedFile   = new UploadedFile( $file->getRealPath(), $file->getBasename() );
        $authorPhoto->setFile( $uploadedFile );
        $this->get( 'vs_cms.app_pictures_uploader' )->upload( $authorPhoto );
        $authorPhoto->setFile( null ); // reset File Because: Serialization of 'Symfony\Component\HttpFoundation\File\UploadedFile' is not allowed
    }
    
    private function removeAuthorPhotos( BookAuthor $author )
    {
        $em         = $this->get( 'doctrine' )->getManager();
        $filesystem = new Filesystem();
        $photosDir  = $this->getParameter( 'vs_application.filemanager_shared_media_gaufrette.app_pictures' );
        
        foreach ( $author->getPhotos() as $photo ) {
            $photoFile  = $photosDir . '/' . $photo->getPath();
            
            $em->remove( $photo );
            $em->flush();
            
            $filesystem->remove( $photoFile );
        }
    }
    
    private function booksPost( BookAuthor &$entity, $formPost )
    {
        $books  = new ArrayCollection();
        $repo   = $this->get( 'vs_catalog.repository.product' );
        
        if ( isset( $formPost['books'] ) ) {
            if ( is_array( $formPost['books'] ) ) {
                foreach ( $formPost['books'] as $bookId ) {
                    $book  = $repo->find( $bookId );
                    if ( $book ) {
                        $books[]   = $book;
                        $entity->addBook( $book );
                        $book->addAuthor( $entity );
                    }
                }
                
                foreach ( $entity->getBooks() as $book ) {
                    if ( ! $books->contains( $book ) ) {
                        $entity->removeBook( $book );
                        $book->removeAuthor( $entity );
                    }
                }
            } else {
                // For Now Not Multiple Categories
                $book   = $repo->find( $formPost['books'] );
                if ( $book ) {
                    $entity->addBook( $book );
                    $book->addAuthor( $entity );
                }
            }
        }
    }
}