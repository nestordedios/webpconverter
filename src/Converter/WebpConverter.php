<?php

namespace App\Converter;

use Bolt\Entity\Field\ImageField;
use Symfony\Component\Filesystem\Filesystem;

class WebpConverter
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var string
     */
    private $webpImageFilePath;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function convertToWebp($imageLocationPath, $imagePath, $imageDestinationPath, $imageQuality = 90): string
    {
        $imageFilename = pathinfo($imageLocationPath, PATHINFO_FILENAME);
        $imageDirectory = pathinfo($imagePath, PATHINFO_DIRNAME);

        $imageDestinationFilePath = $imageDestinationPath . $imageDirectory . '/' . $imageFilename . '.webp';
        $this->setWebpImageFilePath('/webp' . $imageDirectory . '/' . $imageFilename . '.webp');

        // If the Webp file does not exist create the file directory and the Webp image.
        if(!$this->filesystem->exists($imageDestinationFilePath)) {
            $this->createFileDirectory($imageDestinationFilePath);
            $this->createWebpImage($imageLocationPath, $imageDestinationFilePath, $imageQuality);
        }

        return $this->getWebpImageFilePath();
    }

    private function createFileDirectory(string $imageDestinationFilePath)
    {
        $this->filesystem->mkdir(pathinfo($imageDestinationFilePath, PATHINFO_DIRNAME));
    }

    private function createWebpImage($imageLocationPath, $imageDestinationFilePath, $imageQuality)
    {
        $imageExtension = pathinfo($imageLocationPath, PATHINFO_EXTENSION);

        // If the image location path is an ImageField object, get the path to the image and set it to the variable.
        if($imageLocationPath instanceof ImageField) {
            $imageLocationPath = $imageLocationPath->getValue()['path'];
        }

        // Depending on the image extension create the webp image. If no image extension is supported, keep the default
        // image
        switch($imageExtension)
        {
            case 'jpg':
            case 'jpeg':
                $imageResource = imagecreatefromjpeg($imageLocationPath);
                imagewebp($imageResource, $imageDestinationFilePath, $imageQuality);
                imagedestroy($imageResource);
                break;
            case 'png':
                $imageResource = imagecreatefrompng($imageLocationPath);
                imagewebp($imageResource, $imageDestinationFilePath, $imageQuality);
                imagedestroy($imageResource);
                break;
            default:
                $this->setWebpImageFilePath($imageLocationPath);
        }

    }

    /**
     * @return string
     */
    public function getWebpImageFilePath(): string
    {
        return $this->webpImageFilePath;
    }

    /**
     * @param string $webpImageFilePath
     */
    public function setWebpImageFilePath(string $webpImageFilePath): void
    {
        $this->webpImageFilePath = $webpImageFilePath;
    }
}
