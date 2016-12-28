<?php

namespace Mirasvit\Blog\Model;

use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

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
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var UrlInterface
     */
    protected $urlManager;

    /**
     * @param ScopeConfigInterface  $scopeConfig
     * @param StoreManagerInterface $storeManager
     * @param Filesystem            $filesystem
     * @param UrlInterface          $urlManager
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        Filesystem $filesystem,
        UrlInterface $urlManager
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->filesystem = $filesystem;
        $this->urlManager = $urlManager;
    }

    /**
     * @return string
     */
    public function getMenuTitle()
    {
        return $this->scopeConfig->getValue('blog/appearance/menu_title');
    }

    /**
     * @return string
     */
    public function getBlogName()
    {
        return $this->scopeConfig->getValue('blog/appearance/blog_name');
    }

    /**
     * @return string
     */
    public function getBaseMetaTitle()
    {
        return $this->scopeConfig->getValue('blog/seo/base_meta_title');
    }

    /**
     * @return string
     */
    public function getBaseMetaDescription()
    {
        return $this->scopeConfig->getValue('blog/seo/base_meta_description');
    }

    /**
     * @return string
     */
    public function getBaseMetaKeywords()
    {
        return $this->scopeConfig->getValue('blog/seo/base_meta_keywords');
    }

    /**
     * @return string
     */
    public function getBaseRoute()
    {
        return $this->scopeConfig->getValue('blog/seo/base_route');
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->urlManager->getUrl($this->getBaseRoute());
    }

    /**
     * @return string
     */
    public function getPostUrlSuffix()
    {
        return $this->scopeConfig->getValue('blog/seo/post_url_suffix');
    }

    /**
     * @return string
     */
    public function getCategoryUrlSuffix()
    {
        return $this->scopeConfig->getValue('blog/seo/category_url_suffix');
    }


    /**
     * @return string
     */
    public function getDateFormat()
    {
        return $this->scopeConfig->getValue('blog/appearance/date_format');
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
    public function getCommentProvider()
    {
        return $this->scopeConfig->getValue('blog/comments/provider');
    }

    /**
     * @return string
     */
    public function getDisqusShortname()
    {
        return $this->scopeConfig->getValue('blog/comments/disqus_shortname');
    }

    /**
     * @return bool
     */
    public function isAddThisEnabled()
    {
        return $this->scopeConfig->getValue('blog/sharing/enable_addthis');
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
     * @return string
     */
    public function getWidgetMediaPath($dirname)
    {
        $path = $this->getMediaPath() . DIRECTORY_SEPARATOR . $dirname;

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
