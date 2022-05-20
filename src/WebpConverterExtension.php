<?php

namespace App;

use Bolt\Extension\BaseExtension;
use Symfony\Component\Filesystem\Filesystem;

class WebpConverterExtension extends BaseExtension
{
    public function getName(): string
    {
        return "Wepb image converter extension";
    }

    public function install(): void
    {
        $projectDir = $this->getContainer()->getParameter('kernel.project_dir');

        $filesystem = new Filesystem();
        $filesystem->mkdir($projectDir . '/public/webp/');
    }

}
