<?php

namespace Mirasvit\Blog\Block\Adminhtml\Post;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Mirasvit\Blog\Model\ResourceModel\Post\CollectionFactory
     */
    protected $postCollectionFactory;


    public function __construct(
        \Mirasvit\Blog\Model\ResourceModel\Post\CollectionFactory $categoryCollectionFactory,
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        array $data = []
    ) {
        $this->postCollectionFactory = $categoryCollectionFactory;
        $this->context = $context;
        $this->backendHelper = $backendHelper;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     *
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('grid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
        $collection = $this->postCollectionFactory->create();
        $this->setCollection($collection);

        $collection->addAttributeToSelect('*');

//        echo $collection->getSelect();
//        die();

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
        $this->addColumn('user_id', [
            'header'       => __('Author'),
            'index'        => 'user_id',
            'filter_index' => 'main_table.user_id',
            'type'         => 'options',
        ]);
        $this->addColumn('created_at', [
            'header'       => __('Created At'),
            'index'        => 'created_at',
            'filter_index' => 'main_table.created_at',
            'type'         => 'date',
        ]);
        $this->addColumn('updated_at', [
            'header'       => __('Updated At'),
            'index'        => 'updated_at',
            'filter_index' => 'main_table.updated_at',
            'type'         => 'date',
        ]);

        return parent::_prepareColumns();
    }

    /**
     * @return $this
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('article_id');
        $this->getMassactionBlock()->setFormFieldName('article_id');
        $this->getMassactionBlock()->addItem('delete', [
            'label'   => __('Delete'),
            'url'     => $this->getUrl('*/*/massDelete'),
            'confirm' => __('Are you sure?'),
        ]);

        return $this;
    }

    /**
     * @param \Magento\Catalog\Model\Product|\Magento\Framework\DataObject $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', ['id' => $row->getId()]);
    }

    /************************/
}
