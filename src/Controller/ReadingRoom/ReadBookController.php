<?php namespace App\Controller\ReadingRoom;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

use Doctrine\Persistence\ManagerRegistry;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class ReadBookController extends AbstractController
{
    use GlobalFormsTrait;
    
    /** @var ManagerRegistry **/
    private $doctrine;
    
    /** @var RepositoryInterface **/
    private $productRepository;
    
    /** @var string **/
    private $projectRootDir;
    
    public function __construct(
        ManagerRegistry $doctrine,
        RepositoryInterface $productRepository,
        string $projectRootDir
    ) {
        $this->doctrine             = $doctrine;
        $this->productRepository    = $productRepository;
        $this->projectRootDir       = $projectRootDir;
    }
    
    /**
     * Read a video file from storage dir
     *
     * examples: https://ourcodeworld.com/articles/read/329/how-to-send-a-file-as-response-from-a-controller-in-symfony-3
     */
    public function read( $id, Request $request ): Response
    {
        $publicResourcesFolderPath  = $this->projectRootDir . '/docs/TestBooks/';
        $filename                   = "Shogun.pdf";
        $originalName               = 'Shogun.pdf';
        $filePath                   = $publicResourcesFolderPath . $filename;
        
        $response   = new StreamedResponse( function() use ( $filePath )
        {
            $outputStream   = \fopen( 'php://output', 'wb' );
            $fileStream     = \readfile( $filePath );
            
            while ( ! feof( $fileStream ) ) \fwrite( $outputStream, \fread( $fileStream, 1000000 ) );
        });
        
        $response->headers->set( 'Content-Type', 'application/pdf' );
        $response->headers->set( 'Content-Length', \filesize( $filePath ) );
        
        return $response;
    }
}
