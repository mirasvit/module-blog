<?php

namespace Mirasvit\Blog\Repository;

use Magento\Framework\Filter\FilterManager;
use Mirasvit\Blog\Api\Data\TagInterface;
use Mirasvit\Blog\Api\Data\TagInterfaceFactory;
use Mirasvit\Blog\Api\Repository\TagRepositoryInterface;
use Mirasvit\Blog\Model\ResourceModel\Tag\CollectionFactory;
use Mirasvit\Blog\Model\Tag;

class TagRepository implements TagRepositoryInterface
{
    private $factory;

    private $collectionFactory;

    private $filterManager;

    public function __construct(
        TagInterfaceFactory $factory,
        CollectionFactory $collectionFactory,
        FilterManager $filterManager
    ) {
        $this->factory           = $factory;
        $this->collectionFactory = $collectionFactory;
        $this->filterManager     = $filterManager;
    }

    /**
     * {@inheritdoc}
     */
    public function get($id)
    {
        /** @var Tag $model */
        $model = $this->create();

        $model->getResource()->load($model, $id);

        return $model->getId() ? $model : false;
    }

    /**
     * {@inheritdoc}
     */
    public function create()
    {
        return $this->factory->create();
    }

    /**
     * {@inheritdoc}
     */
    public function delete(TagInterface $model)
    {
        /** @var Tag $model */
        return $model->getResource()->delete($model);
    }

    public function ensure(TagInterface $model)
    {
        /** @var TagInterface $tag */
        $tag = $this->getCollection()
            ->addFieldToFilter(TagInterface::NAME, $model->getName())
            ->getFirstItem();

        if ($tag->getId()) {
            return $tag;
        } else {
            return $this->save($model);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCollection()
    {
        return $this->collectionFactory->create();
    }

    /**
     * {@inheritdoc}
     */
    public function save(TagInterface $model)
    {
        if (!$model->getUrlKey()) {
            $model->setUrlKey($this->filterManager->translitUrl($model->getName()));
        }

        /** @var Tag $model */
        $model->getResource()->save($model);

        return $model;
    }
}
