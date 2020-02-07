<?php

namespace Mirasvit\Blog\Block\Sidebar;

use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Mirasvit\Blog\Model\ResourceModel\Tag\Collection;
use Mirasvit\Blog\Model\ResourceModel\Tag\CollectionFactory as TagCollectionFactory;

class TagCloud extends Template
{
    /**
     * @var TagCollectionFactory
     */
    protected $tagCollectionFactory;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var Context
     */
    protected $context;

    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @param TagCollectionFactory $postCollectionFactory
     * @param Registry             $registry
     * @param Context              $context
     * @param array                $data
     */
    public function __construct(
        TagCollectionFactory $postCollectionFactory,
        Registry $registry,
        Context $context,
        array $data = []
    ) {
        $this->tagCollectionFactory = $postCollectionFactory;
        $this->registry             = $registry;
        $this->context              = $context;

        parent::__construct($context, $data);
    }

    /**
     * @return int
     */
    public function getMaxPopularity()
    {
        $max = 0;
        foreach ($this->getCollection() as $tag) {
            if ($tag->getPopularity() > $max) {
                $max = $tag->getPopularity();
            }
        }

        return $max;
    }

    /**
     * @return Collection
     */
    public function getCollection()
    {
        if (!$this->collection) {
            $storeId          = $this->context->getStoreManager()->getStore()->getId();
            $this->collection = $this->tagCollectionFactory->create();
            $this->collection
                ->joinPopularity()
                ->addStoreFilter($storeId);
        }

        return $this->collection;
    }
}
