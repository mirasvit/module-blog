<?php

namespace Mirasvit\Blog\Block\Category;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template\Context;
use Mirasvit\Blog\Model\ResourceModel\Post\CollectionFactory as PostCollectionFactory;
use Mirasvit\Blog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;

class View extends Template implements IdentityInterface
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

    /**
     * @param PostCollectionFactory     $postCollectionFactory
     * @param CategoryCollectionFactory $categoryCollectionFactory
     * @param Registry                  $registry
     * @param Context                   $context
     * @param array                     $data
     */
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
     * @return \Mirasvit\Blog\Model\Category
     */
    public function getCategory()
    {
        return $this->registry->registry('current_blog_category');
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentities()
    {
        return $this->getCategory()->getIdentities();
    }
}
