<?php

namespace Mirasvit\Blog\Block\Post;

use Magento\Cms\Model\Template\FilterProvider;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template\Context;
use Magento\Theme\Block\Html\Breadcrumbs;
use Magento\Theme\Block\Html\Title;
use Mirasvit\Blog\Model\Config;
use Mirasvit\Blog\Model\Post;
use Mirasvit\Blog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;

/**
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
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
     * @var FilterProvider
     */
    protected $filterProvider;

    /**
     * @param CategoryCollectionFactory $postCollectionFactory
     * @param Config                    $config
     * @param FilterProvider            $filterProvider
     * @param Registry                  $registry
     * @param Context                   $context
     */
    public function __construct(
        CategoryCollectionFactory $postCollectionFactory,
        Config $config,
        FilterProvider $filterProvider,
        Registry $registry,
        Context $context
    ) {
        $this->categoryCollectionFactory = $postCollectionFactory;
        $this->config                    = $config;
        $this->filterProvider            = $filterProvider;

        parent::__construct($config, $registry, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentities()
    {
        return $this->getPost()->getIdentities();
    }

    /**
     * @return string
     */
    public function getPostContent()
    {
        return $this->filterProvider->getPageFilter()->filter(
            $this->getPost()->getShortContent() . $this->getPost()->getContent()
        );
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
            ]);

            $breadcrumbs->addCrumb('postname', [
                'label' => $title,
                'title' => $title,
            ]);
        }
    }

    /**
     * @return Post
     */
    public function getPost()
    {
        return $this->registry->registry('current_blog_post');
    }
}
