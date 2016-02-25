<?php

namespace Mirasvit\Blog\Block\Adminhtml\Category;

use Magento\Backend\Block\Widget\Grid\Extended as ExtendedGrid;
use Magento\Backend\Block\Widget\Context;
use Magento\Backend\Helper\Data as BackendHelper;
use Mirasvit\Blog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;

class Grid extends ExtendedGrid
{
    /**
     * @var CategoryCollectionFactory
     */
    protected $categoryCollectionFactory;

    /**
     * @param CategoryCollectionFactory $tagCollectionFactory
     * @param Context                   $context
     * @param BackendHelper             $backendHelper
     * @param array                     $data
     */
    public function __construct(
        CategoryCollectionFactory $tagCollectionFactory,
        Context $context,
        BackendHelper $backendHelper,
        array $data = []
    ) {
        $this->categoryCollectionFactory = $tagCollectionFactory;

        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('blog_category_grid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
        $collection = $this->categoryCollectionFactory->create();
        $this->setCollection($collection);

        $collection->addAttributeToSelect('*');

        return parent::_prepareCollection();
    }

    /**
     * @return $this
     * @throws \Exception
     */
    protected function _prepareColumns()
    {
        $this->addColumn('entity_id', [
            'header' => __('ID'),
            'index'  => 'entity_id'
        ]);

        $this->addColumn('name', [
            'header' => __('Title'),
            'index'  => 'name'
        ]);

        $this->addColumn('is_active', [
            'header'       => __('Active'),
            'index'        => 'is_active',
            'filter_index' => 'main_table.is_active',
            'type'         => 'options',
            'options'      => [
                0 => __('No'),
                1 => __('Yes'),
            ],
        ]);

        return parent::_prepareColumns();
    }

    /**
     * @param \Mirasvit\Blog\Model\Category $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', ['id' => $row->getId()]);
    }
}
