<?php

namespace App\EasyBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;

class SiteExtension extends AbstractExtension
{
    /**
     * @var Package
     */
    private $assetsHelper;

    public function __construct()
    {
        $this->assetsHelper = new Package(new EmptyVersionStrategy());
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('upload_image_path', [$this, 'getUploadImagePath'])
        ];
    }

    public function getUploadImagePath($image)
    {
        return  $this->assetsHelper->getUrl('uploads') . '/'. $image;
    }
}
