<?php namespace App\Controller\ReadingRoom;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\HeaderUtils;

use Doctrine\Persistence\ManagerRegistry;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use League\Flysystem\Filesystem as LeagueFlysystem;
use Vankosoft\CatalogBundle\Component\Product;
use App\Entity\Catalog\ProductFile;

class ReadBookController extends AbstractController
{
    use GlobalFormsTrait;
    
    /** @var ManagerRegistry **/
    private $doctrine;
    
    /** @var RepositoryInterface **/
    private $productRepository;
    
    /** @var LeagueFlysystem */
    private $localFilesystem;
    
    /** @var RepositoryInterface **/
    private $bookmarkRepository;
    
    /** @var FactoryInterface **/
    private $bookmarkFactory;
    
    /** @var string **/
    private $productFilesDir;
    
    /** @var string **/
    private $projectRootDir;
    
    public function __construct(
        ManagerRegistry $doctrine,
        RepositoryInterface $productRepository,
        LeagueFlysystem $localFilesystem,
        RepositoryInterface $bookmarkRepository,
        FactoryInterface $bookmarkFactory,
        string $productFilesDir,
        string $projectRootDir
    ) {
        $this->doctrine             = $doctrine;
        $this->productRepository    = $productRepository;
        $this->localFilesystem      = $localFilesystem;
        $this->bookmarkRepository   = $bookmarkRepository;
        $this->bookmarkFactory      = $bookmarkFactory;
        $this->productFilesDir      = $productFilesDir;
        $this->projectRootDir       = $projectRootDir;
    }
    
    public function read( $id, $locale, Request $request ): Response
    {
        $book           = $this->productRepository->find( $id );
        $bookFiles      = $book->getFiles();
        $contentFile    = $bookFiles[\sprintf( '%s_%s', Product::PRODUCT_FILE_TYPE_CONTENT, $locale )];
        $contentSize    = $this->localFilesystem->fileSize( $contentFile->getPath() );
        // var_dump( $contentFile->getType() ); die;
        
        $filePath                   = $this->productFilesDir . '/' . $contentFile->getPath();
        $response   = new StreamedResponse( function() use ( $filePath, $contentSize )
        {
            $outputStream   = \fopen( 'php://output', 'wb' );
            $fileStream     = \readfile( $filePath );
            
            while ( ! feof( $fileStream ) ) \fwrite( $outputStream, \fread( $fileStream, $contentSize ) );
        });
        
        $response->headers->set( 'Content-Transfer-Encoding', 'binary' );
        $response->headers->set( 'Content-Type', $contentFile->getType() );
        $response->headers->set( 'Content-Length', $contentSize );
        $this->makeContentDisposition( $contentFile, $response );
        
        return $response;
    }
    
    public function getBookmark( $bookId, $bookLocale, $userId, Request $request ): Response
    {
        $bookmark   = $this->bookmarkRepository->findOneBy([
            'bookId' => $bookId,
            'locale' => $bookLocale,
            'userId' => $userId
        ]);
        
        if ( $bookmark ) {
            return new JsonResponse([
                'dateCreated'   => $bookmark->getUpdatedAt(),
                'bookId'        => $bookmark->getBookId(),
                'bookLocale'    => $bookmark->getLocale(),
                'userId'        => $bookmark->getUserId(),
                'page'          => $bookmark->getPage(),
            ]);
        }
        
        return new JsonResponse([
            'dateCreated'   => new \DateTime(),
            'bookId'        => 0,
            'bookLocale'    => 'en_US',
            'userId'        => 0,
            'page'          => 1,
        ]);
    }
    
    public function createBookmark( Request $request ): Response
    {
        $postData = \json_decode( $request->getContent(), true );
        
        $bookmark   = $this->bookmarkRepository->findOneBy([
            'bookId' => $postData['bookId'],
            'locale' => $postData['bookLocale'],
            'userId' => $postData['userId']
        ]);
        
        if ( ! $bookmark ) {
            $bookmark = $this->bookmarkFactory->createNew();
            $bookmark->setBookId( $postData['bookId'] );
            $bookmark->setLocale( $postData['bookLocale'] );
            $bookmark->setUserId( $postData['userId'] );
        }
        
        $bookmark->setPage( $postData['page'] );
        
        $em = $this->doctrine->getManager();
        $em->persist( $bookmark );
        $em->flush();
        
        return new JsonResponse([
            'dateCreated'   => $bookmark->getUpdatedAt(),
            'bookId'        => $bookmark->getBookId(),
            'bookLocale'    => $bookmark->getLocale(),
            'userId'        => $bookmark->getUserId(),
            'page'          => $bookmark->getPage(),
        ]);
    }
    
    private function makeContentDisposition( ProductFile $oFile, Response &$response )
    {
        $transliterator = \Transliterator::create( 'Any-Latin' );
        $transliteratorToASCII = \Transliterator::create( 'Latin-ASCII' );
        $originalName   = $transliteratorToASCII->transliterate(
            $transliterator->transliterate( $oFile->getOriginalName() )
        );
        //var_dump( $originalName ); die;
        
        $disposition    = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            $originalName
        );
        
        $response->headers->set( 'Content-Disposition', $disposition );
    }
}
