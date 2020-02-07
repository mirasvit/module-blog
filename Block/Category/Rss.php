<?php

namespace Mirasvit\Blog\Block\Category;

use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Mirasvit\Blog\Model\Category;
use Mirasvit\Blog\Model\Config;
use Mirasvit\Blog\Model\Post;
use Mirasvit\Blog\Model\ResourceModel\Post\CollectionFactory as PostCollectionFactory;
use Mirasvit\Blog\Model\Url;

class Rss extends Template
{
    /**
     * @var PostCollectionFactory
     */
    protected $postCollectionFactory;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Url
     */
    protected $url;

    /**
     * @param PostCollectionFactory $postCollectionFactory
     * @param Config                $config
     * @param Url                   $url
     * @param Registry              $registry
     * @param Context               $context
     */
    public function __construct(
        PostCollectionFactory $postCollectionFactory,
        Config $config,
        Url $url,
        Registry $registry,
        Context $context
    ) {
        $this->postCollectionFactory = $postCollectionFactory;
        $this->config                = $config;
        $this->url                   = $url;
        $this->registry              = $registry;

        parent::__construct($context);
    }

    /**
     * @return Post[]
     */
    public function getCollection()
    {
        $collection = $this->postCollectionFactory->create()
            ->addAttributeToSelect('*')
            ->addVisibilityFilter()
            ->setOrder('created_at')
            ->setPageSize(10);
        if ($category = $this->getCategory()) {
            $collection->addCategoryFilter($category);
        }

        return $collection;
    }

    /**
     * @return Category|false
     */
    public function getCategory()
    {
        return $this->registry->registry('current_blog_category');
    }

    /**
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return string
     */
    public function getRssUrl()
    {
        return $this->url->getRssUrl($this->getCategory());
    }
}
