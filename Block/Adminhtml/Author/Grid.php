<?php

namespace Mirasvit\Blog\Block\Adminhtml\Author;

use Exception;
use Magento\Backend\Block\Widget\Context;
use Magento\Backend\Block\Widget\Grid\Extended as ExtendedGrid;
use Magento\Backend\Helper\Data as BackendHelper;
use Mirasvit\Blog\Model\Author;
use Mirasvit\Blog\Model\AuthorFactory;

class Grid extends ExtendedGrid
{
    /**
     * @var AuthorFactory
     */
    protected $authorFactory;

    /**
     * @param AuthorFactory $authorFactory
     * @param Context       $context
     * @param BackendHelper $backendHelper
     * @param array         $data
     */
    public function __construct(
        AuthorFactory $authorFactory,
        Context $context,
        BackendHelper $backendHelper,
        array $data = []
    ) {
        $this->authorFactory = $authorFactory;

        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @param Author $row
     *
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', ['id' => $row->getId()]);
    }

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        parent::_construct();

        $this->setId('blog_author_grid');
        $this->setDefaultSort('user_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
        $collection = $this->authorFactory->create()->getCollection();

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * @return $this
     * @throws Exception
     */
    protected function _prepareColumns()
    {
        $this->addColumn('username', [
            'header' => __('Username'),
            'index'  => 'username',
        ]);

        $this->addColumn('firstname', [
            'header' => __('First Name'),
            'index'  => 'firstname',
        ]);

        $this->addColumn('lastname', [
            'header' => __('Last Name'),
            'index'  => 'lastname',
        ]);

        return parent::_prepareColumns();
    }
}
