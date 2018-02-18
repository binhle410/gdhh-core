<?php
namespace App\Service\Media\Provider;


class AppYouTubeProvider extends YouTubeProvider
{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param string $name
     * @param Filesystem $filesystem
     * @param CDNInterface $cdn
     * @param GeneratorInterface $pathGenerator
     * @param ThumbnailInterface $thumbnail
     * @param Browser $browser
     * @param MetadataBuilderInterface $metadata
     * @param bool $html5
     */
    public function __construct($name, Filesystem $filesystem, CDNInterface $cdn, GeneratorInterface $pathGenerator, ThumbnailInterface $thumbnail, Browser $browser, MetadataBuilderInterface $metadata = null, $html5 = false, ContainerInterface $container)
    {
        parent::__construct($name, $filesystem, $cdn, $pathGenerator, $thumbnail, $browser, $metadata);
        $this->html5 = $html5;
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function generatePublicUrl(MediaInterface $media, $format)
    {
        $path = $this->generatePath($media);
        $path = $this->container->getParameter('s3_directory') . '/' . $path;
        return $this->getCdn()->getPath(sprintf('%s/thumb_%s_%s.jpg',
            $path,
            $media->getId(),
            $format
        ), $media->getCdnIsFlushable());
    }

}