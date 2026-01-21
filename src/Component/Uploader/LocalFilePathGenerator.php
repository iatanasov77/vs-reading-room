<?php namespace App\Component\Uploader;

use Symfony\Component\HttpFoundation\File\UploadedFile;

use Vankosoft\CmsBundle\Component\Generator\FilePathGeneratorInterface;
use Vankosoft\CmsBundle\Model\Interfaces\FileInterface;

final class LocalFilePathGenerator implements FilePathGeneratorInterface
{
    public function generate( FileInterface $file ): string
    {
        /** @var UploadedFile $uploadedfile */
        $uploadedfile   = $file->getFile();
        
        $hash   = bin2hex( random_bytes( 16 ) );

        return $this->expandPath( $hash . '.' . $uploadedfile->guessExtension() );
    }

    private function expandPath( string $path ): string
    {
        return sprintf( '%s_%s_%s', substr( $path, 0, 2 ), substr( $path, 2, 2 ), substr( $path, 4 ) );
    }
}
