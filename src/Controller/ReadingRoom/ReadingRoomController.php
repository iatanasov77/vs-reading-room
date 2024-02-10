<?php namespace App\Controller\ReadingRoom;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Component\ReadingRoom;

class ReadingRoomController extends AbstractController
{
    use GlobalFormsTrait;
    
    /** @var ReadingRoom **/
    private $readingRoom;
    
    /** @var ManagerRegistry **/
    private $doctrine;
    
    /** @var ManagerRegistry **/
    private $productCategoryRepository;
    
    /** @var ManagerRegistry **/
    private $productRepository;
    
    public function __construct(
        ReadingRoom $readingRoom,
        ManagerRegistry $doctrine,
        RepositoryInterface $productCategoryRepository,
        RepositoryInterface $productRepository
    ) {
        $this->readingRoom                  = $readingRoom;
        $this->doctrine	                    = $doctrine;
        $this->productCategoryRepository    = $productCategoryRepository;
        $this->productRepository            = $productRepository;
    }
    
    public function readBookAction( $productSlug, Request $request ): Response
    {
        $book   = $this->productRepository->findOneBy( ['slug' => $productSlug] );
        
        return $this->render( 'Pages/read_book.html.twig', [
            'book'                  => $book,
            'readingRoomSettings'   => $this->readingRoom->settings(),
            'shoppingCart'          => $this->getShoppingCart( $request ),
        ]);
    }
}