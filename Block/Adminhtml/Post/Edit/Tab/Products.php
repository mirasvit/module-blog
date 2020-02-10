<?php

namespace Mirasvit\Blog\Block\Adminhtml\Post\Edit\Tab;

use Magento\Backend\Block\Widget\Context;
use Magento\Backend\Block\Widget\Grid\Extended as ExtendedGrid;
use Magento\Backend\Helper\Data as BackendHelper;
use Magento\Catalog\Model\Product\Attribute\Source\Status as ProductStatus;
use Magento\Catalog\Model\Product\Visibility as ProductVisibility;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Framework\Registry;
use Mirasvit\Blog\Model\Post;

class Products extends ExtendedGrid
{
    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var ProductCollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * @var ProductVisibility
     */
    protected $visibility;

    /**
     * @var ProductStatus
     */
    protected $status;


    public function __construct(
        Registry $registry,
        ProductCollectionFactory $productCollectionFactory,
        ProductStatus $status,
        ProductVisibility $visibility,
        Context $context,
        BackendHelper $backendHelper
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->registry                 = $registry;
        $this->visibility               = $visibility;
        $this->status                   = $status;

        parent::__construct($context, $backendHelper);
    }

    /**
     * Retrive grid URL
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl(
            'blog/post/relatedProductsGrid',
            ['_current' => true]
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('related_product_grid');
        $this->setDefaultSort('entity_id');
        $this->setHtmlIdPrefix('aa');
        $this->setUseAjax(true);

        if ($this->getPost() && $this->getPost()->getId()) {
            $this->setDefaultFilter(['in_products' => 1]);
        }
    }

    /**
     * @return Post
     */
    public function getPost()
    {
        return $this->registry->registry('current_model');
    }

    /**
     * {@inheritdoc}
     */
    protected function _addColumnFilterToCollection($column)
    {
        // Set custom filter for in product flag
        if ($column->getId() == 'in_products') {
            $productIds = $this->getSelectedProducts();
            if (empty($productIds)) {
                $productIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', ['in' => $productIds]);
            } else {
                if ($productIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', ['nin' => $productIds]);
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }

        return $this;
    }

    /**
     * Retrieve selected related products
     * @return array
     */
    protected function getSelectedProducts()
    {
        $products = $this->getProductsRelated();
        if (!is_array($products)) {
            $products = array_keys($this->getSelectedRelatedProducts());
        }

        return $products;
    }

    /**
     * Retrieve related products
     * @return array
     */
    public function getSelectedRelatedProducts()
    {
        $products = [];
        foreach ($this->getPost()->getRelatedProducts() as $product) {
            $products[$product->getId()] = ['position' => 0];
        }

        return $products;
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
        $collection = $this->productCollectionFactory->create()
            ->addAttributeToSelect('*');

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * {@inheritdoc}
     */
    protected function _prepareColumns()
    {
        $this->addColumn('in_products', [
            'type'             => 'checkbox',
            'name'             => 'in_products',
            'values'           => $this->getSelectedProducts(),
            'align'            => 'center',
            'index'            => 'entity_id',
            'header_css_class' => 'col-select',
            'column_css_class' => 'col-select',
        ]);

        $this->addColumn('position', [
            'header'           => __('Position'),
            'name'             => 'position',
            'type'             => 'number',
            'validate_class'   => 'validate-number',
            'index'            => 'position',
            'editable'         => true,
            'is_system'        => 1,
            'header_css_class' => 'col-hidden',
            'column_css_class' => 'col-hidden',
        ]);

        $this->addColumn('id', [
            'header' => __('ID'),
            'type'   => 'number',
            'index'  => 'entity_id',
        ]);

        $this->addColumn('name', [
            'header' => __('Name'),
            'index'  => 'name',
        ]);

        $this->addColumn('sku', [
            'header' => __('SKU'),
            'index'  => 'sku',
        ]);

        $this->addColumn('status', [
            'header'  => __('Status'),
            'index'   => 'status',
            'type'    => 'options',
            'options' => $this->status->getOptionArray(),
        ]);

        $this->addColumn('visibility', [
            'header'  => __('Visibility'),
            'index'   => 'visibility',
            'type'    => 'options',
            'options' => $this->visibility->getOptionArray(),
        ]);
    }
}
