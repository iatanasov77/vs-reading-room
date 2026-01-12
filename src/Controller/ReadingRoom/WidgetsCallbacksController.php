<?php namespace App\Controller\ReadingRoom;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Vankosoft\CatalogBundle\Component\AssociationStrategy;
use App\Component\ReadingRoom;

class WidgetsCallbacksController extends AbstractController
{
    /** @var ReadingRoom **/
    private $readingRoom;
    
    /** @var RepositoryInterface **/
    private $booksRepository;
    
    public function __construct(
        ReadingRoom $readingRoom,
        RepositoryInterface $booksRepository
    ) {
        $this->readingRoom      = $readingRoom;
        $this->booksRepository  = $booksRepository;
    }
    
    public function getBookSuggestionsAction( $productSlug, Request $request ): Response
    {
        $book   = $this->booksRepository->findOneBy( ['slug' => $productSlug] );
        $suggestionsStrategy    = $this->readingRoom->getSuggestionsStrategy()->getAssociationStrategy();
        
        switch ( $suggestionsStrategy ) {
            case AssociationStrategy::STRATEGY_SIMILAR:
                $bookSuggestions    = new ArrayCollection();
                foreach ( $book->getGenres() as $genre ) {
                    $bookSuggestions = new ArrayCollection( $bookSuggestions->toArray() + $genre->getBooks()->toArray() );
                    if ( $bookSuggestions->contains( $book ) ) {
                        $bookSuggestions->removeElement( $book );
                    }
                }
                break;
            case AssociationStrategy::STRATEGY_RANDOM:
            default:
                $bookSuggestions       = $this->booksRepository->getRandomProducts( 6 );
        }
        
        return $this->render( 'WidgetsCallbacks/book-suggestions.html.twig', [
            'bookSuggestions'   => $bookSuggestions,
        ]);
    }
}