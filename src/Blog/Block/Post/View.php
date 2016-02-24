<?php

namespace Mirasvit\Blog\Block\Post;

use Magento\Framework\View\Element\Template;

class View extends Template
{
    /**
     * @var \Mirasvit\Kb\Model\ResourceModel\Category\CollectionFactory
     */
    protected $categoryCollectionFactory;

    /**
     * @var \Mirasvit\Kb\Model\Config
     */
    protected $config;

    /**
     * @var \Mirasvit\Kb\Helper\Data
     */
    protected $kbData;

    /**
     * @var \Magento\Catalog\Helper\Data
     */
    protected $catalogData;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magento\Framework\View\Element\Template\Context
     */
    protected $context;


    public function __construct(
        \Mirasvit\Blog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Mirasvit\Blog\Model\Config $config,
        \Magento\Catalog\Helper\Data $catalogData,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\View\Element\Template\Context $context
    ) {
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->config = $config;
        $this->catalogData = $catalogData;
        $this->registry = $registry;
        $this->context = $context;

        parent::__construct($context);
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $post = $this->getPost();

        if ($headBlock = $this->getLayout()->getBlock('head')) {
            $headBlock->setTitle($post->getMetaTitle() ? $post->getMetaTitle() : $post->getName());
            $headBlock->setDescription($post->getMetaDescription());
            $headBlock->setKeywords($post->getMetaKeywords());
        }

        $metaTitle = $post->getMetaTitle();
        if (!$metaTitle) {
            $metaTitle = $post->getName();
        }

        $metaDescription = $post->getMetaDescription();
        if (!$metaDescription) {
            $metaDescription = $this->filterManager->truncate(
                $this->filterManager->stripTags($post->getContent()),
                ['length' => 150, 'etc' => ' ...', 'remainder' => '', 'breakWords' => false]
            );
        }

        $this->pageConfig->getTitle()->set($metaTitle);
        $this->pageConfig->setDescription($post->getName() . ' ' . $metaDescription);
        $this->pageConfig->setKeywords($post->getMetaKeywords());

        /** @var \Magento\Theme\Block\Html\Breadcrumbs $breadcrumbs */
        if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {

            $breadcrumbs->addCrumb('home', [
                'label' => __('Home'),
                'title' => __('Go to Home Page'),
                'link'  => $this->context->getUrlBuilder()->getBaseUrl(),
            ]);

            $category = $post->getCategory();
            if ($category) {
                $parents = $this->categoryCollectionFactory->create()
                    ->addNameToSelect()
                    ->addFieldToFilter('entity_id', $category->getParentIds())
                    ->setOrder('level', 'asc');

                foreach ($parents as $cat) {
                    $breadcrumbs->addCrumb('kbase' . $cat->getUrlKey(), [
                        'label' => $cat->getName(),
                        'title' => $cat->getName(),
                        'link'  => $cat->getUrl(),
                    ]);
                }

                $breadcrumbs->addCrumb('kbase' . $category->getUrlKey(), [
                    'label' => $category->getName(),
                    'title' => $category->getName(),
                    'link'  => $category->getUrl(),
                ]);
            }

            $breadcrumbs->addCrumb('kbase' . $post->getUrlKey(), [
                'label' => $post->getName(),
                'title' => $post->getName(),
            ]);
        }

        $pageMainTitle = $this->getLayout()->getBlock('page.main.title');
        if ($pageMainTitle) {
            $pageMainTitle->setPageTitle($post->getName());
        }
    }

    /**
     * @return string
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getKbPageTitle()
    {
        return $this->getLayout()->getBlock('page.main.title')->toHtml();
    }

    /**
     * @return \Mirasvit\Blog\Model\Post
     */
    public function getPost()
    {
        return $this->registry->registry('current_blog_post');
    }

    /**
     * @return \Mirasvit\Kb\Model\ResourceModel\Category\Collection
     */
    public function getCategories()
    {
        $collection = $this->getArticle()->getCategories()
            ->addFieldToFilter('is_active', true);

        return $collection;
    }

}
