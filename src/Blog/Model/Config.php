<?php

namespace Mirasvit\Blog\Model;

use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\UrlInterface;

class Config
{
    const MEDIA_FOLDER = 'blog';

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @param StoreManagerInterface $storeManager
     * @param Filesystem            $filesystem
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        Filesystem $filesystem
    ) {
        $this->storeManager = $storeManager;
        $this->filesystem = $filesystem;
    }

    /**
     * @return string
     */
    public function getDefaultSortField()
    {
        return 'created_at';
    }

    /**
     * @return string
     */
    public function getMediaPath()
    {
        $path = $this->filesystem
                ->getDirectoryRead(DirectoryList::MEDIA)
                ->getAbsolutePath() . self::MEDIA_FOLDER;

        if (!file_exists($path) || !is_dir($path)) {
            $this->filesystem
                ->getDirectoryWrite(DirectoryList::MEDIA)
                ->create($path);
        }

        return $path;
    }

    /**
     * @param string $image
     * @return string
     */
    public function getMediaUrl($image)
    {
        if (!$image) {
            return false;

        }
        $url = $this->storeManager->getStore()
                ->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . self::MEDIA_FOLDER;

        $url .= '/' . $image;

        return $url;
    }
}