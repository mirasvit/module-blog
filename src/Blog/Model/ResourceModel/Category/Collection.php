<?php
namespace Mirasvit\Blog\Model\ResourceModel\Category;

use Magento\Eav\Model\Entity\Collection\AbstractCollection;
use Magento\Framework\App\ObjectManager;

class Collection extends AbstractCollection
{
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init('Mirasvit\Blog\Model\Category', 'Mirasvit\Blog\Model\ResourceModel\Category');
    }

    /**
     * @return $this
     */
    public function addNameToSelect()
    {
        return $this->addAttributeToSelect('*');
    }

    /**
     * @param int|null $parentId
     * @return \Mirasvit\Blog\Model\Category[]
     */
    public function getTree($parentId = null)
    {
        $list = [];

        if ($parentId == null) {
            $parentId = 0;
        }

        $collection = ObjectManager::getInstance()
            ->create('Mirasvit\Blog\Model\ResourceModel\Category\Collection')
            ->addAttributeToSelect('*')
            ->addFieldToFilter('parent_id', $parentId)
            ->setOrder('position', 'asc');

        foreach ($collection as $item) {
            $list[$item->getId()] = $item;
            if ($item->getChildrenCount()) {
                $childrens = $this->getTree($item->getId());
                foreach ($childrens as $child) {
                    $list[$child->getId()] = $child;
                }
            }
        }

        return $list;
    }

    /**
     * {@inheritdoc}
     */
    protected function _toOptionArray($valueField = 'id', $labelField = 'name', $additional = [])
    {
        $result = [];

        foreach ($this->getTree() as $item) {
            $result[] = [
                'value' => $item->getId(),
                'label' => str_repeat(' ', $item->getLevel() * 5) . $item->getName()
            ];
        }

        return $result;
    }
}