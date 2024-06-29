<?php namespace App\Component;

use Vankosoft\ApplicationBundle\Component\Context\ApplicationContextInterface;
use App\Entity\ReadingRoomSettings;
use App\Component\Exception\ReadingRoomSettingsException;

final class ReadingRoom
{
    /** @var ReadingRoomSettings */
    private $readingRoomSettings;
    
    public function __construct(
        ApplicationContextInterface $applicationContext
    ) {
        $readingRoomApplication     = $applicationContext->getApplication()->getReadingRoomApplication();
        if ( ! $readingRoomApplication ) {
            throw new ReadingRoomSettingsException( 'Reading Room Settings IS NOT Configured for this Application !!!"' );
        }
        
        $this->readingRoomSettings  = $readingRoomApplication->getSettings();
    }
    
    public function settings(): ReadingRoomSettings
    {
        return $this->readingRoomSettings;
    }
    
    public function getSuggestionsStrategy()
    {
        return $this->readingRoomSettings->getBookSuggestionsAssociationType();
    }
}