<?php namespace App\Component;

use Sylius\Component\Resource\Repository\RepositoryInterface;
use App\Entity\ReadingRoomSettings;
use App\Component\Exception\ReadingRoomSettingsException;

final class ReadingRoom
{
    /** @var ReadingRoomSettings */
    private $readingRoomSettings;
    
    public function __construct(
        string $settingsKey,
        RepositoryInterface $readingRoomSettingsRepository
    ) {
        $readingRoomSettings          = $readingRoomSettingsRepository->findOneBy( ['settingsKey' => $settingsKey] );
        if ( ! $readingRoomSettings ) {
            throw new ReadingRoomSettingsException( 'Reading Room Settings Key: "' . $settingsKey . '" Not Found !!!' );
        }
        
        $this->readingRoomSettings  = $readingRoomSettings;
    }
    
    public function settings(): ReadingRoomSettings
    {
        return $this->readingRoomSettings;
    }
}