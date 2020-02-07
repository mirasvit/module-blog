<?php

namespace Mirasvit\Blog\Model\Config;

use Exception;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Magento\Framework\UrlInterface;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Store\Model\StoreManagerInterface;
use Mirasvit\Blog\Model\Config;

class FileProcessor
{
    /**
     * @var UploaderFactory
     */
    private $uploaderFactory;

    /**
     * Media Directory object (writable).
     * @var WriteInterface
     */
    private $mediaDirectory;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    public function __construct(
        UploaderFactory $uploaderFactory,
        Filesystem $filesystem,
        StoreManagerInterface $storeManager
    ) {
        $this->uploaderFactory = $uploaderFactory;
        $this->storeManager    = $storeManager;
        $this->mediaDirectory  = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
    }

    /**
     * @param string $fileId
     *
     * @return array
     * @throws LocalizedException
     */
    public function save($fileId)
    {
        try {
            $result         = $this->saveFile($fileId, $this->getAbsoluteMediaPath());
            $result['name'] = $result['file'];
            $result['url']  = $this->getMediaUrl($result['file']);
        } catch (Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }

        return $result;
    }

    /**
     * @param string $fileId
     * @param string $destination
     *
     * @return array
     * @throws LocalizedException
     */
    private function saveFile($fileId, $destination)
    {
        $uploader = $this->uploaderFactory->create(['fileId' => $fileId]);
        $uploader->setAllowRenameFiles(true);
        $uploader->setFilesDispersion(false);

        return $uploader->save($destination);
    }

    /**
     * Retrieve absolute temp media path
     * @return string
     */
    private function getAbsoluteMediaPath()
    {
        return $this->mediaDirectory->getAbsolutePath(Config::MEDIA_FOLDER);
    }

    /**
     * @param string $file
     *
     * @return string
     */
    private function getMediaUrl($file)
    {
        return $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA)
            . Config::MEDIA_FOLDER . '/' . $file;
    }
}
