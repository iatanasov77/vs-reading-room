<?php namespace App\Controller\ReadingRoom;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Sylius\Component\Resource\Repository\RepositoryInterface;

use Knp\Component\Pager\PaginatorInterface;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;

class AuthorController extends AbstractController
{
    /** @var RepositoryInterface */
    private $authorsRepository;
    
    /** @var int **/
    private $itemsPerPage;
    
    public function __construct(
        RepositoryInterface $authorsRepository,
        int $itemsPerPage
    ) {
        $this->authorsRepository    = $authorsRepository;
        $this->itemsPerPage         = $itemsPerPage;
    }
    
    public function index( Request $request, PaginatorInterface $paginator ): Response
    {
        $authors   = $this->authorsRepository->findBy( [], ['updatedAt' => 'DESC'] );
        $resources  = new Pagerfanta( new ArrayAdapter( $authors ) );
        $resources->setMaxPerPage( $this->itemsPerPage );
        
        $currentPage    = $request->query->get( 'page' );
        if ( $currentPage ) {
            $resources->setCurrentPage( $currentPage );
        }
        /*
        $authors = $paginator->paginate(
            $this->authorsRepository->getQueryBuilder( 'bp' )->orderBy( 'bp.updatedAt', 'DESC' ),
            $request->query->getInt( 'page', 1 ) // page number,
            $this->itemsPerPage // limit per page
        );
        */
        
        return $this->render( 'Pages/Authors/index.html.twig', [
            'authors'   => $resources, //$authors,
        ]);
    }
    
    public function details( $slug, Request $request ): Response
    {
        $author  = $this->authorsRepository->findOneBy( ['slug' => $slug] );
        
        return $this->render( 'Pages/Authors/details.html.twig', [
            'author'    => $author,
        ]);
    }
}