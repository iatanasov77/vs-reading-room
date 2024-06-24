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
        $readingRoomSettings    = $applicationContext->getApplication()->getReadingRoomApplication();
        if ( ! $readingRoomSettings ) {
            throw new ReadingRoomSettingsException( 'Reading Room Settings IS NOT Configured for this Application !!!"' );
        }
        
        $this->readingRoomSettings    = $readingRoomSettings->getSettings();
    }
    
    public function settings(): ReadingRoomSettings
    {
        return $this->readingRoomSettings;
    }
}