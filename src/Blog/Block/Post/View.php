<?php

namespace Mirasvit\Blog\Block\Post;

use Magento\Framework\View\Element\Template;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\DataObject\IdentityInterface;
use Mirasvit\Blog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use Mirasvit\Blog\Model\Config;

class View extends Template implements IdentityInterface
{
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
     * @var Config
     */
    protected $config;

    /**
     * @param CategoryCollectionFactory $tagCollectionFactory
     * @param Config                    $config
     * @param Registry                  $registry
     * @param Context                   $context
     */
    public function __construct(
        CategoryCollectionFactory $tagCollectionFactory,
        Config $config,
        Registry $registry,
        Context $context
    ) {
        $this->categoryCollectionFactory = $tagCollectionFactory;
        $this->config = $config;
        $this->registry = $registry;
        $this->context = $context;

        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
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
                $ids = $category->getParentIds();
                $ids[] = 0;
                $parents = $this->categoryCollectionFactory->create()
                    ->addNameToSelect()
                    ->addFieldToFilter('entity_id', $ids)
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
     * @return \Mirasvit\Blog\Model\Post
     */
    public function getPost()
    {
        return $this->registry->registry('current_blog_post');
    }

    public function getIdentities()
    {
        return $this->getPost()->getIdentities();
    }
}