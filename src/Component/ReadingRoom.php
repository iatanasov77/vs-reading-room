<?php namespace App\Component;

use Symfony\Contracts\Translation\TranslatorInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Vankosoft\ApplicationBundle\Component\Context\ApplicationContextInterface;
use Vankosoft\CmsBundle\Component\Uploader\FileUploaderInterface;
use Vankosoft\CatalogBundle\Component\Product;
use App\Entity\ReadingRoomSettings;
use App\Component\Exception\ReadingRoomSettingsException;

final class ReadingRoom
{
    const BOOK_TYPE_PDF = 'pdf';
    const BOOK_TYPE_HTML = 'html';
    const BOOK_TYPE_VANKOSOFT_DOCUMENT = 'vankosoft_document';
    
    const BOOK_TRANSLATION_EN_US = 'en_US';
    const BOOK_TRANSLATION_BG_BG = 'bg_BG';
    
    /** @var TranslatorInterface */
    private $translator;
    
    /** @var ReadingRoomSettings */
    private $readingRoomSettings;
    
    /** @var RepositoryInterface **/
    private $productRepository;
    
    /** @var FileUploaderInterface **/
    private $htmlBookUploader;
    
    /** @var string **/
    private $htmlBooksDir;
    
    public function __construct(
        ApplicationContextInterface $applicationContext,
        TranslatorInterface $translator,
        RepositoryInterface $productRepository,
        FileUploaderInterface $htmlBookUploader,
        string $htmlBooksDir
    ) {
        $this->translator           = $translator;
        
        $readingRoomApplication     = $applicationContext->getApplication()->getReadingRoomApplication();
        if ( ! $readingRoomApplication ) {
            throw new ReadingRoomSettingsException( 'Reading Room Settings IS NOT Configured for this Application !!!"' );
        }
        
        $this->readingRoomSettings  = $readingRoomApplication->getSettings();
        
        $this->productRepository    = $productRepository;
        $this->htmlBookUploader     = $htmlBookUploader;
        $this->htmlBooksDir         = $htmlBooksDir;
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
            self::BOOK_TYPE_HTML                => $this->translator->trans( 'reading_room.form.product.book_type_html', [], 'ReadingRoom' ),
            //self::BOOK_TYPE_VANKOSOFT_DOCUMENT  => $this->translator->trans( 'reading_room.form.product.book_type_vankosoft_document', [], 'ReadingRoom' ),
        ];
    }
    
    public function bookTranslations(): array
    {
        return [
            self::BOOK_TRANSLATION_EN_US    => $this->translator->trans( 'reading_room.form.product.book_translation_en', [], 'ReadingRoom' ),
            self::BOOK_TRANSLATION_BG_BG    => $this->translator->trans( 'reading_room.form.product.book_translation_bg', [], 'ReadingRoom' ),
        ];
    }
    
    public function getHtmlBookUrl( $id, $locale ): string
    {
        $book           = $this->productRepository->find( $id );
        $bookFiles      = $book->getFiles();
        $contentFile    = $bookFiles[\sprintf( '%s_%s', Product::PRODUCT_FILE_TYPE_CONTENT, $locale )];
        // var_dump( $contentFile->getType() ); die;
        
        $archiveFile = \sprintf( "%s/%s",
            $this->htmlBooksDir,
            $contentFile->getPath()
        );
        $archiveFileName = \pathinfo( $archiveFile, PATHINFO_FILENAME );
        
        return $this->htmlBookUploader->getFilesystem()->publicUrl( \sprintf( "%s/index.html", $archiveFileName ) );
    }
}