<?php

namespace Mirasvit\Blog\Repository;

use Mirasvit\Blog\Api\Data\TagInterface;
use Mirasvit\Blog\Api\Repository\TagRepositoryInterface;
use Mirasvit\Blog\Model\Tag;
use Mirasvit\Blog\Model\ResourceModel\Tag\CollectionFactory;
use Mirasvit\Blog\Api\Data\TagInterfaceFactory;
use Magento\Framework\Filter\FilterManager;

class TagRepository implements TagRepositoryInterface
{
    /**
     * @var TagInterfaceFactory
     */
    private $factory;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var FilterManager
     */
    private $filterManager;

    public function __construct(
        TagInterfaceFactory $factory,
        CollectionFactory $collectionFactory,
        FilterManager $filterManager
    ) {
        $this->factory = $factory;
        $this->collectionFactory = $collectionFactory;
        $this->filterManager = $filterManager;
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
    public function create()
    {
        return $this->factory->create();
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
    public function delete(TagInterface $model)
    {
        /** @var Tag $model */
        return $model->getResource()->delete($model);
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
}