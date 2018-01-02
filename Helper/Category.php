<?php
namespace Mirasvit\Blog\Helper;

class Category extends \Magento\Framework\App\Helper\AbstractHelper
{

    /**
     * @param \Mirasvit\Blog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Mirasvit\Blog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Magento\Framework\App\Helper\Context $context
    ) {
        $this->categoryCollectionFactory = $categoryCollectionFactory;

        parent::__construct($context);
    }

    /**
     * @return \Mirasvit\Blog\Model\Category|false
     */
    public function getRootCategory()
    {
        $category   = false;
        $collection = $this->categoryCollectionFactory->create()
            ->addFieldToFilter('parent_id', 0);

        if ($collection->count()) {
            $category = $collection->getFirstItem();
        }

        return $category;
    }
}
