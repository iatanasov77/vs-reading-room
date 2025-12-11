<?php namespace App\Controller\ReadingRoom;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Knp\Component\Pager\PaginatorInterface;

class AuthorController extends AbstractController
{
    /** @var RepositoryInterface */
    private $authorsRepository;
    
    public function __construct(
        RepositoryInterface $authorsRepository
    ) {
        $this->authorsRepository    = $authorsRepository;
    }
    
    public function index( Request $request, PaginatorInterface $paginator ): Response
    {
        $authors = $paginator->paginate(
            $this->authorsRepository->getQueryBuilder( 'bp' )->orderBy( 'bp.updatedAt', 'DESC' ),
            $request->query->getInt( 'page', 1 ) /*page number*/,
            18 /*limit per page*/
        );
        
        return $this->render( 'Pages/Authors/index.html.twig', [
            'authors'   => $authors,
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