<?php namespace App\Widgets;

use Vankosoft\ApplicationBundle\EventListener\Widgets\WidgetLoaderInterface;
use Vankosoft\ApplicationBundle\Component\Widget\Builder\Item;
use Vankosoft\ApplicationBundle\EventListener\Event\WidgetEvent;

use Symfony\Component\HttpFoundation\RequestStack;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Vankosoft\CatalogBundle\Component\AssociationStrategy;
use App\Component\ReadingRoom;

/**
 * @TODO Create Catalog Associations to use for suggestions
 * 
 * EXAMPLES
 * ========
 * 'product_association', 'product_association_type' Resources Defined in Sylius\Bundle\ProductBundle\DependencyInjection\Configuration
 * 
 * https://docs.sylius.com/en/latest/book/products/product_associations.html
 */
class BookSuggestionsWidget implements WidgetLoaderInterface
{
    /** @var RequestStack */
    private $requestStack;
    
    /** @var RepositoryInterface */
    private $booksRepository;
    
    /** @var ReadingRoom **/
    private $readingRoom;
    
    public function __construct(
        RequestStack $requestStack,
        RepositoryInterface $booksRepository,
        ReadingRoom $readingRoom
    ) {
        $this->requestStack     = $requestStack;
        $this->booksRepository  = $booksRepository;
        $this->readingRoom      = $readingRoom;
    }
    
    public function builder( WidgetEvent $event )
    {
        $request    = $this->requestStack->getMainRequest();
        //$request    = $this->requestStack->getCurrentRequest();
        $bookSlug   = $request->attributes->get( 'productSlug' );
        
        /** @var Widget */
        $widgetContainer    = $event->getWidgetContainer();
        
        /** @var Item */
        $widgetItem = $widgetContainer->createWidgetItem( 'book-suggestions' );
        if( $widgetItem ) {
            
            $widgetItem->setTemplate( 'Widgets/book_suggestions.html.twig', [
                //'locales'   => $this->localesRepository->findAll(),
                'bookSlug'         => $bookSlug,
                'bookSuggestions'  => $this->getBookSuggestions( $bookSlug ),
            ]);
            
            // Add Widgets
            $widgetContainer->addWidget( $widgetItem );
        }
    }
    
    private function getBookSuggestions( $bookSlug )
    {
        $book                   = $this->booksRepository->findOneBy( ['slug' => $bookSlug] );
        $suggestionsStrategy    = $this->readingRoom->getSuggestionsStrategy()->getAssociationStrategy();
        
        switch ( $suggestionsStrategy ) {
            case AssociationStrategy::STRATEGY_SIMILAR:
                $bookSuggestions    = new ArrayCollection();
                foreach ( $book->getGenres() as $genre ) {
                    $bookSuggestions = new ArrayCollection( $bookSuggestions->toArray() + $genre->getBooks()->toArray() ); 
                }
                break;
            case AssociationStrategy::STRATEGY_RANDOM:
            default:
                $bookSuggestions       = $this->booksRepository->getRandomProducts( 6 );
        }
        
        return $bookSuggestions;
    }
}