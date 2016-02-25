<?php
namespace Mirasvit\Blog\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\DataObject;

class Url
{
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var PostFactory
     */
    protected $postFactory;

    /**
     * @var CategoryFactory
     */
    protected $categoryFactory;

    /**
     * @var UrlInterface
     */
    protected $urlManager;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param PostFactory          $postFactory
     * @param CategoryFactory      $categoryFactory
     * @param UrlInterface         $urlManager
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        PostFactory $postFactory,
        CategoryFactory $categoryFactory,
        UrlInterface $urlManager
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->postFactory = $postFactory;
        $this->categoryFactory = $categoryFactory;
        $this->urlManager = $urlManager;
    }

    /**
     * @return string
     */
    public function getBasePath()
    {
        return 'blog';
    }

    /**
     * @param Post $post
     * @return string
     */
    public function getPostUrl($post)
    {
        return $this->urlManager->getUrl($this->getBasePath() . '/' . $post->getUrlKey());
    }

    /**
     * @param Category $category
     * @return string
     */
    public function getCategoryUrl($category)
    {
        return $this->urlManager->getUrl($this->getBasePath() . '/' . $category->getUrlKey());
    }

    /**
     * @param string $pathInfo
     * @return bool|DataObject
     */
    public function match($pathInfo)
    {
        $identifier = trim($pathInfo, '/');
        $parts = explode('/', $identifier);

        if (count($parts) == 1) {
            $parts[0] = $this->trimSuffix($parts[0]);
        }

        if ($parts[0] != $this->getBasePath()) {
            return false;
        }

        if (count($parts) > 1) {
            unset($parts[0]);
            $urlKey = implode('/', $parts);
            $urlKey = urldecode($urlKey);
            $urlKey = $this->trimSuffix($urlKey);
        } else {
            $urlKey = '';
        }


        $post = $this->postFactory->create()->getCollection()
            ->addAttributeToFilter('url_key', $urlKey)
            ->getFirstItem();

        if ($post->getId()) {
            return new DataObject([
                'module_name'     => 'blog',
                'controller_name' => 'post',
                'action_name'     => 'view',
                'params'          => ['id' => $post->getId()],
            ]);
        }

        $category = $this->categoryFactory->create()->getCollection()
            ->addAttributeToFilter('url_key', $urlKey)
            ->getFirstItem();

        if ($category->getId()) {
            return new DataObject([
                'module_name'     => 'blog',
                'controller_name' => 'category',
                'action_name'     => 'view',
                'params'          => ['id' => $category->getId()],
            ]);
        }

        return false;
    }

    /**
     * Return url without suffix
     *
     * @param string $key
     * @return string
     */
    protected function trimSuffix($key)
    {
        $configUrlSuffix = $this->scopeConfig->getValue('catalog/seo/product_url_suffix');
        //user can enter .html or html suffix
        if ($configUrlSuffix != '' && $configUrlSuffix[0] != '.') {
            $configUrlSuffix = '.' . $configUrlSuffix;
        }

        $key = str_replace($configUrlSuffix, '', $key);

        return $key;
    }
}