<?php

namespace Mirasvit\Blog\Block\Catalog;

use Magento\Catalog\Model\Product;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Mirasvit\Blog\Model\Config;
use Mirasvit\Blog\Model\Post;
use Mirasvit\Blog\Model\ResourceModel\Post\Collection;
use Mirasvit\Blog\Model\ResourceModel\Post\CollectionFactory as PostCollectionFactory;
use Mirasvit\Blog\Model\Url;

class RelatedPosts extends Template
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

        $this->setTabTitle();
    }

    /**
     * Set tab title
     * @return void
     */
    public function setTabTitle()
    {
        $size  = $this->getCollection()->count();
        $title = $size
            ? __('Related Posts %1', '<span class="counter">' . $size . '</span>')
            : '';
        $this->setTitle($title);
    }

    /**
     * @return Collection|Post[]
     */
    public function getCollection()
    {
        $collection = $this->postCollectionFactory->create()
            ->addAttributeToSelect('*')
            ->addVisibilityFilter()
            ->addStoreFilter($this->_storeManager->getStore()->getId())
            ->setOrder('created_at', 'desc');

        if ($product = $this->getProduct()) {
            $collection->addRelatedProductFilter($product);
        }

        return $collection;
    }

    /**
     * @return Product|false
     */
    public function getProduct()
    {
        return $this->registry->registry('current_product');
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
