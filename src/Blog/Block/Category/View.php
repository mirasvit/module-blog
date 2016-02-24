<?php

namespace Mirasvit\Blog\Block\Category;

use Magento\Framework\View\Element\Template;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template\Context;
use Mirasvit\Blog\Model\ResourceModel\Post\CollectionFactory as PostCollectionFactory;
use Mirasvit\Blog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;

class View extends Template
{
    /**
     * @var PostCollectionFactory
     */
    protected $postCollectionFactory;

    /**
     * @var CategoryCollectionFactory
     */
    protected $categoryCollectionFactory;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var Context
     */
    protected $context;

    public function __construct(
        PostCollectionFactory $postCollectionFactory,
        CategoryCollectionFactory $categoryCollectionFactory,
        Registry $registry,
        Context $context,
        array $data = []
    ) {
        $this->postCollectionFactory = $postCollectionFactory;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->registry = $registry;
        $this->context = $context;

        parent::__construct($context, $data);
    }

    /**
     * @return $this
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $category = $this->getCategory();

        $metaTitle = $category->getMetaTitle();
        if (!$metaTitle) {
            $metaTitle = $category->getName();
        }

        $metaDescription = $category->getMetaDescription();
        if (!$metaDescription) {
            $metaDescription = $metaTitle;
        }

        $this->pageConfig->getTitle()->set($metaTitle);
        $this->pageConfig->setDescription($metaDescription);
        $this->pageConfig->setKeywords($category->getMetaKeywords());

        if ($category && ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs'))) {
            $breadcrumbs->addCrumb('home', [
                'label' => __('Home'),
                'title' => __('Go to Home Page'),
                'link'  => $this->context->getUrlBuilder()->getBaseUrl(),
            ]);

            $ids = $category->getParentIds();

            $ids[] = 0;
            $parents = $this->categoryCollectionFactory->create()
                ->addFieldToFilter('entity_id', $ids)
                ->addNameToSelect()
                ->setOrder('level', 'asc');
            /** @var \Mirasvit\Blog\Model\Category $cat */
            foreach ($parents as $cat) {
                $breadcrumbs->addCrumb($cat->getId(), [
                    'label' => $cat->getName(),
                    'title' => $cat->getName(),
                    'link'  => $cat->getUrl(),
                ]);
            }

            $breadcrumbs->addCrumb($category->getId(), [
                'label' => $category->getName(),
                'title' => $category->getName(),
            ]);
        }

        return $this;
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
     * @return \Mirasvit\Blog\Model\Category
     */
    public function getCategory()
    {
        return $this->registry->registry('current_blog_category');
    }

    /**
     * @return $this
     */
    public function getCategoryCollection()
    {
        $collection = $this->categoryCollectionFactory->create()
            ->addFieldToFilter('parent_id', $this->getCategory()->getId())
            ->addFieldToFilter('is_active', true)
            ->addStoreIdFilter($this->context->getStoreManager()->getStore()->getId())
            ->setOrder('position', 'asc');

        return $collection;
    }

    /**
     * @param object $category
     *
     * @return $this
     */
    public function getArticleCollection($category)
    {
        $collection = $this->articleCollectionFactory->create()
            ->addCategoryIdFilter($category->getId())
            ->addFieldToFilter('main_table.is_active', true)
            ->addStoreIdFilter($this->context->getStoreManager()->getStore()->getId())
            ->setPageSize($this->config->getGeneralArticleLinksLimit())
            ->setOrder('position', 'asc');

        return $collection;
    }

    /**
     * @return int
     */
    public function getPageLimit()
    {
        return $this->config->getGeneralArticleLinksLimit();
    }

    /************************/

    /**
     * @param object $category
     *
     * @return int
     */
    public function getArticlesNumber($category)
    {
        return $category->getArticlesNumber();
    }

    /**
     * @return string
     */
    public function getArticleListHtml()
    {
        return $this->getChildHtml('kb.article_list');
    }

    /**
     * Return identifiers for produced content.
     *
     * @return array
     */
    public function getIdentities()
    {
        return [Article::CACHE_KB_ARTICLE_CATEGORY . '_' . $this->getCategory()->getId()];
    }
}
