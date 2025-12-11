<?php namespace App\Component\Uploader;

use League\Flysystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Webmozart\Assert\Assert;

use Vankosoft\CmsBundle\Component\Uploader\AbstractFileUploader;
use Vankosoft\CmsBundle\Component\Generator\FilePathGeneratorInterface;
use Vankosoft\CmsBundle\Component\Generator\UploadedFilePathGenerator;
use Vankosoft\CmsBundle\Model\Interfaces\FileInterface;

class BookUploader extends AbstractFileUploader
{
    /** @var Filesystem */
    protected $filesystem;
    
    /** @var FilePathGeneratorInterface */
    protected $filePathGenerator;
    
    public function __construct(
        Filesystem $filesystem,
        ?FilePathGeneratorInterface $filePathGenerator = null
    ) {
            $this->filesystem = $filesystem;
            
            if ( $filePathGenerator === null ) {
                @trigger_error( sprintf(
                    'Not passing an $filePathGenerator to %s constructor is deprecated since Sylius 1.6 and will be not possible in Sylius 2.0.',
                    self::class
                ), \E_USER_DEPRECATED );
            }
            
            $this->filePathGenerator = $filePathGenerator ?? new UploadedFilePathGenerator();
    }
    
    public function upload( FileInterface $filemanagerFile ): void
    {
        if ( ! $filemanagerFile->hasFile() ) {
            return;
        }
        
        $file = $filemanagerFile->getFile();
        
        /** @var File $file */
        Assert::isInstanceOf( $file, File::class );
        
        if ( null !== $filemanagerFile->getPath() && $this->has( $filemanagerFile->getPath() ) ) {
            $this->remove( $filemanagerFile->getPath() );
        }
        
        do {
            $path = $this->filePathGenerator->generate( $filemanagerFile );
        } while ( $this->isAdBlockingProne( $path ) || $this->filesystem->has( $path ) );
        
        $filemanagerFile->setPath( $path );
        
        $this->filesystem->write(
            $filemanagerFile->getPath(),
            file_get_contents( $filemanagerFile->getFile()->getPathname() )
        );
        
        $filemanagerFile->setType( $this->filesystem->mimeType( $filemanagerFile->getPath() ) );
    }
}
