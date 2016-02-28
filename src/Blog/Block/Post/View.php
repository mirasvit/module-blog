<?php

namespace Mirasvit\Blog\Block\Post;

use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\DataObject\IdentityInterface;
use Mirasvit\Blog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use Mirasvit\Blog\Model\Config;

class View extends AbstractBlock implements IdentityInterface
{
    /**
     * @var CategoryCollectionFactory
     */
    protected $categoryCollectionFactory;

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

        parent::__construct($config, $registry, $context);
    }

    /**
     * {@inheritdoc}
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $post = $this->getPost();

        $title = $post ? $post->getName() : $this->config->getBlogName();

        $metaTitle = $post
            ? ($post->getMetaTitle() ? $post->getMetaTitle() : $post->getName())
            : $this->config->getBaseMetaTitle();

        $metaDescription = $post
            ? ($post->getMetaDescription() ? $post->getMetaDescription() : $post->getName())
            : $this->config->getBaseMetaDescription();

        $metaKeywords = $post
            ? ($post->getMetaKeywords() ? $post->getMetaKeywords() : $post->getName())
            : $this->config->getBaseMetaKeywords();

        $this->pageConfig->getTitle()->set($metaTitle);
        $this->pageConfig->setDescription($metaDescription);
        $this->pageConfig->setKeywords($metaKeywords);

        /** @var \Magento\Theme\Block\Html\Title $pageMainTitle */
        $pageMainTitle = $this->getLayout()->getBlock('page.main.title');
        if ($pageMainTitle) {
            $pageMainTitle->setPageTitle($title);
        }

        /** @var \Magento\Theme\Block\Html\Breadcrumbs $breadcrumbs */
        if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
            $breadcrumbs->addCrumb('home', [
                'label' => __('Home'),
                'title' => __('Go to Home Page'),
                'link'  => $this->context->getUrlBuilder()->getBaseUrl(),
            ])->addCrumb('blog', [
                'label' => $this->config->getBlogName(),
                'title' => $this->config->getBlogName(),
                'link'  => $this->config->getBaseUrl()
            ]);

            if ($category = $post->getCategory()) {
                $ids = $category->getParentIds();
                $ids[] = 0;
                $parents = $this->categoryCollectionFactory->create()
                    ->addNameToSelect()
                    ->excludeRoot()
                    ->addFieldToFilter('entity_id', $ids)
                    ->setOrder('level', 'asc');

                foreach ($parents as $cat) {
                    $breadcrumbs->addCrumb('blog' . $cat->getUrlKey(), [
                        'label' => $cat->getName(),
                        'title' => $cat->getName(),
                        'link'  => $cat->getUrl(),
                    ]);
                }

                $breadcrumbs->addCrumb('blog' . $category->getUrlKey(), [
                    'label' => $category->getName(),
                    'title' => $category->getName(),
                    'link'  => $category->getUrl(),
                ]);
            }

            $breadcrumbs->addCrumb('blog' . $post->getUrlKey(), [
                'label' => $post->getName(),
                'title' => $post->getName(),
            ]);
        }
    }

    /**
     * @return \Mirasvit\Blog\Model\Post
     */
    public function getPost()
    {
        return $this->registry->registry('current_blog_post');
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentities()
    {
        return $this->getPost()->getIdentities();
    }
}