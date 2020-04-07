<?php

namespace Mirasvit\Blog\Controller\Adminhtml\Post;

use Exception;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Area;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Registry;
use Mirasvit\Blog\Api\Data\PostInterface;
use Mirasvit\Blog\Api\Repository\PostRepositoryInterface;
use Mirasvit\Blog\Api\Repository\TagRepositoryInterface;
use Mirasvit\Blog\Controller\Adminhtml\Post;

class Save extends Post
{
    /**
     * @var TagRepositoryInterface
     */
    private $tagRepository;

    /**
     * @var JsonFactory
     */
    private $jsonFactory;

    public function __construct(
        TagRepositoryInterface $tagRepository,
        JsonFactory $jsonFactory,
        PostRepositoryInterface $postRepository,
        Registry $registry,
        Context $context
    ) {
        $this->tagRepository = $tagRepository;
        $this->jsonFactory   = $jsonFactory;
        parent::__construct($postRepository, $registry, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $id             = $this->getRequest()->getParam(PostInterface::ID);
        $resultRedirect = $this->resultRedirectFactory->create();
        $data           = $this->filterPostData($this->getRequest()->getParams());
        if ($data) {
            /** @var \Mirasvit\Blog\Model\Post $model */
            $model = $this->initModel();
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This post no longer exists.'));

                return $resultRedirect->setPath('*/*/');
            }
            $model->addData($data);

            if (!$data['is_short_content']) {
                $model->setShortContent('');
            }

            try {
                if ($this->getRequest()->getParam('isAjax')) {
                    return $this->handlePreviewRequest($model);
                } else {
                    $this->postRepository->save($model);
                    $this->removeRevisions($model);
                    $this->messageManager->addSuccessMessage(__('You saved the post.'));
                    if ($this->getRequest()->getParam('back') == 'edit') {
                        return $resultRedirect->setPath('*/*/edit', [PostInterface::ID => $model->getId()]);
                    }

                    return $this->context->getResultRedirectFactory()->create()->setPath('*/*/');
                }
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());

                return $resultRedirect->setPath(
                    '*/*/edit',
                    [PostInterface::ID => $this->getRequest()->getParam(PostInterface::ID)]
                );
            }
        } else {
            $resultRedirect->setPath('*/*/');
            $this->messageManager->addErrorMessage('No data to save.');

            return $resultRedirect;
        }
    }

    /**
     * @param array $rawData
     *
     * @return array
     */
    private function filterPostData(array $rawData)
    {
        $data = $rawData;
        foreach ([PostInterface::FEATURED_IMAGE] as $key) {
            if (isset($data[$key]) && is_array($data[$key])) {
                if (!empty($data[$key]['delete'])) {
                    $data[$key] = null;
                } else {
                    if (isset($data[$key][0]['name'])) {
                        $data[$key] = $data[$key][0]['name'];
                    }
                }
            }
        }
        if (!isset($data[PostInterface::FEATURED_IMAGE])) {
            $data[PostInterface::FEATURED_IMAGE] = '';
        }
        if (isset($data[PostInterface::TAG_IDS]) && is_array($data[PostInterface::TAG_IDS])) {
            foreach ($data[PostInterface::TAG_IDS] as $idx => $tagId) {
                if (!is_numeric($tagId)) {
                    $tag                                = $this->tagRepository->create()
                        ->setName($tagId);
                    $tag                                = $this->tagRepository->ensure($tag);
                    $data[PostInterface::TAG_IDS][$idx] = $tag->getId();
                }
            }
        } else {
            $data[PostInterface::TAG_IDS] = [null];
        }
        if (!isset($data['category_ids'])) {
            $data['category_ids'] = [null];
        }
        if (isset($data['blog_post_form_product_listing'])) {
            $productIds = [];
            foreach ($data['blog_post_form_product_listing'] as $item) {
                $productIds[] = $item['entity_id'];
            }
            $data[PostInterface::PRODUCT_IDS] = $productIds;
        } else {
            $data[PostInterface::PRODUCT_IDS] = [null];
        }

        return $data;
    }

    private function handlePreviewRequest(PostInterface $model)
    {
        $om            = ObjectManager::getInstance();
        $scopeResolver = $om->create('Magento\Framework\Url\ScopeResolverInterface', [
            'areaCode' => Area::AREA_FRONTEND,
        ]);
        # preview mode save as revision

        if ($id = $model->getId()) {
            $model->setParentId($id);
        }
        $model->setId(false);
        $model->setType(PostInterface::TYPE_REVISION);
        $this->postRepository->save($model);
        $resultJson = $this->jsonFactory->create();
        $url        = $om->create('Magento\Framework\Url', ['scopeResolver' => $scopeResolver])
            ->getUrl('blog/post/view', [
                PostInterface::ID => $model->getId(),
                '_scope_to_url'   => false,
                '_nosid'          => true,
            ]);

        return $resultJson->setData([
            PostInterface::ID => $model->getId(),
            'url'             => $url,
        ]);
    }

    /**
     * Remove preview versions of particular post and all previews of not saved posts
     *
     * @param PostInterface $model
     */
    private function removeRevisions(PostInterface $model)
    {
        $collection = $this->postRepository->getCollection();
        $collection->addFieldToFilter('type', PostInterface::TYPE_REVISION);
        $collection->addFieldToFilter('parent_id', ['in' => [0, $model->getId()]]);
        foreach ($collection as $item) {
            $this->postRepository->delete($item);
        }
    }
}
