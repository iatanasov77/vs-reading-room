<?php namespace App\Component;

use Symfony\Contracts\Translation\TranslatorInterface;
use Vankosoft\ApplicationBundle\Component\Context\ApplicationContextInterface;
use App\Entity\ReadingRoomSettings;
use App\Component\Exception\ReadingRoomSettingsException;

final class ReadingRoom
{
    const BOOK_TYPE_PDF = 'pdf';
    const BOOK_TYPE_VANKOSOFT_DOCUMENT = 'vankosoft_document';
    
    /** @var TranslatorInterface */
    private $translator;
    
    /** @var ReadingRoomSettings */
    private $readingRoomSettings;
    
    public function __construct(
        ApplicationContextInterface $applicationContext,
        TranslatorInterface $translator
    ) {
        $this->translator           = $translator;
        
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
    
    public function getEnableRegistration()
    {
        return $this->readingRoomSettings->getEnableRegistration();
    }
    
    public function bookTypes(): array
    {
        return [
            self::BOOK_TYPE_PDF                 => $this->translator->trans( 'reading_room.form.product.book_type_pdf', [], 'ReadingRoom' ),
            self::BOOK_TYPE_VANKOSOFT_DOCUMENT  => $this->translator->trans( 'reading_room.form.product.book_type_vankosoft_document', [], 'ReadingRoom' ),
        ];
    }
}