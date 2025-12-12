<?php namespace App\Widgets;

use Vankosoft\ApplicationBundle\EventListener\Widgets\WidgetLoaderInterface;
use Vankosoft\ApplicationBundle\Component\Widget\Widget;
use Vankosoft\ApplicationBundle\Component\Widget\Builder\Item;
use Vankosoft\ApplicationBundle\EventListener\Event\WidgetEvent;

use App\Component\ReadingRoom;

final class ApplicationAuthenticationWidget implements WidgetLoaderInterface
{
    /** @var ReadingRoom **/
    private $readingRoom;
    
    public function __construct(
        ReadingRoom $readingRoom
    ) {
        $this->readingRoom  = $readingRoom;
    }
    
    public function builder( WidgetEvent $event )
    {
        $enableRegistration    = $this->readingRoom->getEnableRegistration();
        
        /** @var Widget */
        $widgetContainer    = $event->getWidgetContainer();
        
        /** @var Item */
        $widgetItem = $widgetContainer->createWidgetItem( 'application-authentication', false );
        if( $widgetItem ) {
            $widgetItem->setTemplate( 'Widgets/application_authentication.html.twig', [
                'enableRegistration'    => $enableRegistration,
            ]);
            
            // Add Widgets
            $widgetContainer->addWidget( $widgetItem );
        }
    }
}