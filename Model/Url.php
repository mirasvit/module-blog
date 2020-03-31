<?php

namespace Mirasvit\Blog\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Url as UrlManager;
use Magento\Store\Model\StoreManagerInterface;
use Mirasvit\Blog\Api\Data\CategoryInterface;
use Mirasvit\Blog\Api\Data\PostInterface;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Url
{
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

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
     * @var TagFactory
     */
    protected $tagFactory;

    /**
     * @var AuthorFactory
     */
    protected $authorFactory;

    /**
     * @var UrlManager
     */
    protected $urlManager;

    /**
     * @var Config
     */
    protected $config;

    public function __construct(
        StoreManagerInterface $storeManager,
        Config $config,
        ScopeConfigInterface $scopeConfig,
        PostFactory $postFactory,
        CategoryFactory $categoryFactory,
        TagFactory $tagFactory,
        AuthorFactory $authorFactory,
        UrlManager $urlManager
    ) {
        $this->storeManager = $storeManager;
        $this->config = $config;
        $this->scopeConfig = $scopeConfig;
        $this->postFactory = $postFactory;
        $this->categoryFactory = $categoryFactory;
        $this->tagFactory = $tagFactory;
        $this->authorFactory = $authorFactory;
        $this->urlManager = $urlManager;
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->urlManager->getUrl($this->config->getBaseRoute());
    }

    /**
     * @param $post
     * @param bool $useSid
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getPostUrl($post, $useSid = true)
    {
        $storeCode = $this->storeManager->getStore($post->getStoreId())->getCode();
        return $this->getUrl(
            '/' . $post->getUrlKey(),
            'post',
            ['_nosid' => !$useSid, '_scope' => $storeCode]
        );
    }

    /**
     * @param string $route
     * @param string $type
     * @param array $urlParams
     *
     * @return string
     */
    protected function getUrl($route, $type, $urlParams = [])
    {
        $url = $this->urlManager->getUrl($this->config->getBaseRoute() . $route, $urlParams);

        if ($type == 'post' && $this->config->getPostUrlSuffix()) {
            $url = $this->addSuffix($url, $this->config->getPostUrlSuffix());
        }

        if ($type == 'category' && $this->config->getCategoryUrlSuffix()) {
            $url = $this->addSuffix($url, $this->config->getCategoryUrlSuffix());
        }

        return $url;
    }

    /**
     * @param string $url
     * @param string $suffix
     *
     * @return string
     */
    private function addSuffix($url, $suffix)
    {
        $parts = explode('?', $url, 2);
        $parts[0] = rtrim($parts[0], '/') . $suffix;

        return implode('?', $parts);
    }

    /**
     * @param Category $category
     * @param array $urlParams
     *
     * @return string
     */
    public function getCategoryUrl($category, $urlParams = [])
    {
        return $this->getUrl('/' . $category->getUrlKey(), 'category', $urlParams);
    }

    /**
     * @param Category $category
     *
     * @return string
     */
    public function getRssUrl($category = null)
    {
        if ($category) {
            return $this->getUrl('/rss/' . $category->getUrlKey(), 'rss');
        }

        return $this->getUrl('/rss', 'rss');
    }

    /**
     * @param Tag $tag
     * @param array $urlParams
     *
     * @return string
     */
    public function getTagUrl($tag, $urlParams = [])
    {
        return $this->getUrl('/tag/' . strtolower($tag->getUrlKey()), 'tag', $urlParams);
    }

    /**
     * @param Author $author
     * @param array $urlParams
     *
     * @return string
     */
    public function getAuthorUrl($author, $urlParams = [])
    {
        return $this->getUrl('/author/' . strtolower($author->getId()), 'author', $urlParams);
    }

    /**
     * @param array $urlParams
     *
     * @return string
     */
    public function getSearchUrl($urlParams = [])
    {
        return $this->getUrl('/search/', 'search', $urlParams);
    }

    /**
     * @param string $pathInfo
     *
     * @return bool|DataObject
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function match($pathInfo)
    {
        $identifier = trim($pathInfo, '/');
        $parts = explode('/', $identifier);

        if (count($parts) >= 1) {
            $parts[count($parts) - 1] = $this->trimSuffix($parts[count($parts) - 1]);
        }

        if ($parts[0] != $this->config->getBaseRoute()) {
            return false;
        }

        if (count($parts) > 1) {
            unset($parts[0]);
            $parts = array_values($parts);
            $urlKey = implode('/', $parts);
            $urlKey = urldecode($urlKey);
            $urlKey = $this->trimSuffix($urlKey);
        } else {
            $urlKey = '';
        }

        if ($urlKey == '') {
            return new DataObject([
                'module_name' => 'blog',
                'controller_name' => 'category',
                'action_name' => 'index',
                'params' => [],
            ]);
        }

        if ($parts[0] == 'search') {
            return new DataObject([
                'module_name' => 'blog',
                'controller_name' => 'search',
                'action_name' => 'result',
                'params' => [],
            ]);
        }

        if ($parts[0] == 'tag' && isset($parts[1])) {
            $tag = $this->tagFactory->create()->getCollection()
                ->addFieldToFilter('url_key', $parts[1])
                ->getFirstItem();

            if ($tag->getId()) {
                return new DataObject([
                    'module_name' => 'blog',
                    'controller_name' => 'tag',
                    'action_name' => 'view',
                    'params' => ['id' => $tag->getId()],
                ]);
            } else {
                return false;
            }
        }

        if ($parts[0] == 'author' && isset($parts[1])) {
            $author = $this->authorFactory->create()->getCollection()
                ->addFieldToFilter('main_table.user_id', $parts[1])
                ->getFirstItem();

            if ($author->getId()) {
                return new DataObject([
                    'module_name' => 'blog',
                    'controller_name' => 'author',
                    'action_name' => 'view',
                    'params' => ['id' => $author->getId()],
                ]);
            } else {
                return false;
            }
        }

        if ($parts[0] == 'rss' && isset($parts[1])) {
            $category = $this->categoryFactory->create()->getCollection()
                ->addFieldToFilter('url_key', $parts[1])
                ->getFirstItem();

            if ($category->getId()) {
                return new DataObject([
                    'module_name' => 'blog',
                    'controller_name' => 'category',
                    'action_name' => 'rss',
                    'params' => [CategoryInterface::ID => $category->getId()],
                ]);
            } else {
                return false;
            }
        } elseif ($parts[0] == 'rss') {
            return new DataObject([
                'module_name' => 'blog',
                'controller_name' => 'category',
                'action_name' => 'rss',
                'params' => [],
            ]);
        }

        $post = $this->postFactory->create()->getCollection()
            ->addPostFilter()
            ->addAttributeToFilter('url_key', $urlKey)
            ->addStoreFilter($this->storeManager->getStore()->getId())
            ->getFirstItem();

        if ($post->getId()) {
            return new DataObject([
                'module_name' => 'blog',
                'controller_name' => 'post',
                'action_name' => 'view',
                'params' => [PostInterface::ID => $post->getId()],
            ]);
        }

        $category = $this->categoryFactory->create()->getCollection()
            ->addAttributeToFilter('url_key', $urlKey)
            ->getFirstItem();

        if ($category->getId()) {
            return new DataObject([
                'module_name' => 'blog',
                'controller_name' => 'category',
                'action_name' => 'view',
                'params' => [CategoryInterface::ID => $category->getId()],
            ]);
        }

        return false;
    }

    /**
     * Return url without suffix
     *
     * @param string $key
     *
     * @return string
     */
    protected function trimSuffix($key)
    {
        $suffix = $this->config->getCategoryUrlSuffix();
        //user can enter .html or html suffix
        if ($suffix != '' && $suffix[0] != '.') {
            $suffix = '.' . $suffix;
        }

        $key = str_replace($suffix, '', $key);

        $suffix = $this->config->getPostUrlSuffix();
        //user can enter .html or html suffix
        if ($suffix != '' && $suffix[0] != '.') {
            $suffix = '.' . $suffix;
        }

        $key = str_replace($suffix, '', $key);

        return $key;
    }
}
