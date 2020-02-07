<?php

namespace Mirasvit\Blog\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Helper\Js as JsHelper;
use Magento\Backend\Model\View\Result\Page\Interceptor;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Registry;
use Magento\Store\Model\StoreManagerInterface;
use Mirasvit\Blog\Api\Data\PostInterface;
use Mirasvit\Blog\Api\Repository\PostRepositoryInterface;
use Mirasvit\Blog\Model\PostFactory;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
abstract class Post extends Action
{
    /**
     * @var PostFactory
     */
    protected $postRepository;

    /**
     * @var Context
     */
    protected $context;

    /**
     * @var StoreManagerInterface
     */
    //    protected $storeManager;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var JsonFactory
     */
    //    protected $jsonFactory;

    /**
     * @var JsHelper
     */
    //    protected $jsHelper;

    public function __construct(
        PostRepositoryInterface $postRepository,
        //        StoreManagerInterface $storeManager,
        //        JsonFactory $jsonFactory,
        //        JsHelper $jsHelper,
        Registry $registry,
        //        TimezoneInterface $localeDate,
        Context $context
    ) {
        $this->postRepository = $postRepository;
        //        $this->storeManager = $storeManager;
        //        $this->jsonFactory = $jsonFactory;
        //        $this->jsHelper = $jsHelper;
        $this->registry = $registry;
        //        $this->localeDate = $localeDate;
        $this->context = $context;

        $this->resultFactory = $context->getResultFactory();

        parent::__construct($context);
    }

    /**
     * @return PostInterface
     */
    public function initModel()
    {
        $model = $this->postRepository->create();
        $id    = $this->getRequest()->getParam(PostInterface::ID);

        if ($id && !is_array($id)) {
            $model = $this->postRepository->get($id);
        }

        $this->registry->register('current_model', $model);

        return $model;
    }

    /**
     * {@inheritdoc}
     * @param Interceptor $resultPage
     *
     * @return Interceptor
     */
    protected function initPage($resultPage)
    {
        $resultPage->setActiveMenu('Mirasvit_Blog::blog');
        $resultPage->getConfig()->getTitle()->prepend(__('Mirasvit Blog MX'));
        $resultPage->getConfig()->getTitle()->prepend(__('Posts'));

        return $resultPage;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->context->getAuthorization()->isAllowed('Mirasvit_Blog::blog_post');
    }
}
