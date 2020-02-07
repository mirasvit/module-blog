<?php

namespace Mirasvit\Blog\Block\Search;

use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Theme\Block\Html\Breadcrumbs;
use Magento\Theme\Block\Html\Title;
use Mirasvit\Blog\Model\Config;
use Mirasvit\Blog\Model\ResourceModel\Post\Collection;

class Result extends Template
{
    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var Context
     */
    protected $context;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @param Config   $config
     * @param Registry $registry
     * @param Context  $context
     * @param array    $data
     */
    public function __construct(
        Config $config,
        Registry $registry,
        Context $context,
        array $data = []
    ) {
        $this->config   = $config;
        $this->registry = $registry;
        $this->context  = $context;

        parent::__construct($context, $data);
    }

    /**
     * @return Collection
     */
    public function getPostCollection()
    {
        return $this->getChildBlock('blog.post.list')->getPostCollection();
    }

    /**
     * @return $this
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $title = $metaTitle = __("Search results for: '%1'", $this->getRequest()->getParam('q'));

        $metaDescription = $this->config->getBaseMetaDescription();

        $metaKeywords = $this->config->getBaseMetaKeywords();

        $this->pageConfig->getTitle()->set($metaTitle);
        $this->pageConfig->setDescription($metaDescription);
        $this->pageConfig->setKeywords($metaKeywords);

        /** @var Title $pageMainTitle */
        $pageMainTitle = $this->getLayout()->getBlock('page.main.title');
        if ($pageMainTitle) {
            $pageMainTitle->setPageTitle($title);
        }

        /** @var Breadcrumbs $breadcrumbs */
        if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
            $breadcrumbs->addCrumb('home', [
                'label' => __('Home'),
                'title' => __('Go to Home Page'),
                'link'  => $this->context->getUrlBuilder()->getBaseUrl(),
            ])->addCrumb('blog', [
                'label' => $this->config->getBlogName(),
                'title' => $this->config->getBlogName(),
                'link'  => $this->config->getBaseUrl(),
            ])->addCrumb('search', [
                'label' => $title,
                'title' => $title,
            ]);
        }

        return $this;
    }
}
