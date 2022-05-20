<?php

namespace App\Twig;

use App\Converter\WebpConverter;
use Psr\Container\ContainerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TwigExtension extends AbstractExtension
{
    /**
     * @var WebpConverter
     */
    private $imageConverter;
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(WebpConverter $imageConverter, ContainerInterface $container)
    {
        $this->imageConverter = $imageConverter;
        $this->container = $container;
    }

    /**
     * Returns a list of filters to add to the existing list.
     *
     * @return TwigFilter[]
     */
    public function getFilters()
    {
        return [
            new TwigFilter('convertToWebp', [$this, 'getWebpImage']),
        ];
    }

    public function getWebpImage($imagePath): string
    {
        $projectDir = $this->container->getParameter('kernel.project_dir');
        $imageDestinationPath = $projectDir . "/public/webp/";

        $imageLocationPath = $projectDir . "/public" . $imagePath;

        return $this->imageConverter->convertToWebp($imageLocationPath, $imagePath, $imageDestinationPath);
    }
}
