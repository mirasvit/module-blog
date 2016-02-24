<?php
namespace Mirasvit\Blog\Model\ResourceModel;

use Magento\Framework\DataObject;
use Magento\Eav\Model\Entity\AbstractEntity;
use Magento\Framework\App\ObjectManager;

class Category extends AbstractEntity
{
    public function getEntityType()
    {
        if (empty($this->_type)) {
            $this->setType(\Mirasvit\Blog\Model\Category::ENTITY);
        }
        return parent::getEntityType();
    }

    /**
     * {@inheritdoc}
     */
    protected function _beforeSave(DataObject $object)
    {
        /** @var \Mirasvit\Blog\Model\Category $object */

        parent::_beforeSave($object);

        if (!$object->getChildrenCount()) {
            $object->setChildrenCount(0);
        }

        if ($object->isObjectNew()) {
            if (!$object->hasParentId()) {
                $object->setParentId(1);
            }

            $parent = ObjectManager::getInstance()
                ->create('Mirasvit\Blog\Model\Category')
                ->load($object->getParentId());

            $object->setPath($parent->getPath());

            if ($object->getPosition() === null) {
                $object->setPosition($this->getMaxPosition($object->getPath()) + 1);
            }

            $path = explode('/', $object->getPath());
            $level = count($path) - ($object->getId() ? 1 : 0);
            $toUpdateChild = array_diff($path, [$object->getId()]);

            if (!$object->hasPosition()) {
                $object->setPosition($this->getMaxPosition(implode('/', $toUpdateChild)) + 1);
            }

            if (!$object->hasLevel()) {
                $object->setLevel($level);
            }

            if (!$object->getId() && $object->getPath()) {
                $object->setPath($object->getPath() . '/');
            }

            $this->getConnection()->update(
                $this->getEntityTable(),
                ['children_count' => new \Zend_Db_Expr('children_count+1')],
                ['entity_id IN(?)' => $toUpdateChild]
            );
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function _afterSave(DataObject $object)
    {
        /** @var \Mirasvit\Blog\Model\Category $object */

        if (substr($object->getPath(), -1) == '/' || !$object->getPath()) {
            $object->setPath($object->getPath() . $object->getId());
            $this->savePath($object);
        }

        if ($object->dataHasChangedFor('parent_id')) {
            $newParent = \Magento\Framework\App\ObjectManager::getInstance()
                ->create('Mirasvit\Blog\Model\Category')
                ->load($object->getParentId());
            $this->changeParent($object, $newParent);
        }

        return parent::_afterSave($object);
    }

    /**
     * Update path field
     *
     * @param \Mirasvit\Blog\Model\Category $object
     * @return $this
     */
    protected function savePath($object)
    {
        if ($object->getId()) {
            $this->getConnection()->update(
                $this->getEntityTable(),
                ['path' => $object->getPath()],
                ['entity_id = ?' => $object->getId()]
            );
            $object->unsetData('path_ids');
        }

        return $this;
    }

    /**
     * Move category to another parent node
     *
     * @param \Mirasvit\Blog\Model\Category $category
     * @param \Mirasvit\Blog\Model\Category $newParent
     * @param null|int                      $afterCategoryId
     * @return $this
     */
    public function changeParent(
        \Mirasvit\Blog\Model\Category $category,
        \Mirasvit\Blog\Model\Category $newParent,
        $afterCategoryId = null
    ) {
        $childrenCount = $this->getChildrenCount($category->getId()) + 1;
        $table = $this->getEntityTable();
        $connection = $this->getConnection();
        $levelFiled = $connection->quoteIdentifier('level');
        $pathField = $connection->quoteIdentifier('path');

        /**
         * Decrease children count for all old category parent categories
         */
        $connection->update(
            $table,
            ['children_count' => new \Zend_Db_Expr('children_count - ' . $childrenCount)],
            ['entity_id IN(?)' => $category->getParentIds()]
        );

        /**
         * Increase children count for new category parents
         */
        $connection->update(
            $table,
            ['children_count' => new \Zend_Db_Expr('children_count + ' . $childrenCount)],
            ['entity_id IN(?)' => $newParent->getPathIds()]
        );

        $position = $this->processPositions($category, $newParent, $afterCategoryId);

        $newPath = sprintf('%s/%s', $newParent->getPath(), $category->getId());
        $newLevel = $newParent->getLevel() + 1;
        $levelDisposition = $newLevel - $category->getLevel();

        /**
         * Update children nodes path
         */
        $connection->update(
            $table,
            [
                'path'  => new \Zend_Db_Expr(
                    'REPLACE(' . $pathField . ',' . $connection->quote(
                        $category->getPath() . '/'
                    ) . ', ' . $connection->quote(
                        $newPath . '/'
                    ) . ')'
                ),
                'level' => new \Zend_Db_Expr($levelFiled . ' + ' . $levelDisposition)
            ],
            [$pathField . ' LIKE ?' => $category->getPath() . '/%']
        );

        /**
         * Update moved category data
         */
        $data = [
            'path'      => $newPath,
            'level'     => $newLevel,
            'position'  => $position,
            'parent_id' => $newParent->getId(),
        ];
        $connection->update($table, $data, ['entity_id = ?' => $category->getId()]);

        // Update category object to new data
        $category->addData($data);
        $category->unsetData('path_ids');

        return $this;
    }

    /**
     * Get child categories count
     *
     * @param int $categoryId
     * @return int
     */
    public function getChildrenCount($categoryId)
    {
        $select = $this->getConnection()->select()->from(
            $this->getEntityTable(),
            'children_count'
        )->where(
            'entity_id = :entity_id'
        );
        $bind = ['entity_id' => $categoryId];

        return $this->getConnection()->fetchOne($select, $bind);
    }

    /**
     * @param \Mirasvit\Blog\Model\Category $category
     * @param \Mirasvit\Blog\Model\Category $newParent
     * @param null|int                      $afterCategoryId
     * @return int
     */
    protected function processPositions($category, $newParent, $afterCategoryId)
    {
        $table = $this->getEntityTable();
        $connection = $this->getConnection();
        $positionField = $connection->quoteIdentifier('position');

        $bind = ['position' => new \Zend_Db_Expr($positionField . ' - 1')];
        $where = [
            'parent_id = ?'         => $category->getParentId(),
            $positionField . ' > ?' => $category->getPosition(),
        ];
        $connection->update($table, $bind, $where);

        /**
         * Prepare position value
         */
        if ($afterCategoryId) {
            $select = $connection->select()->from($table, 'position')->where('entity_id = :entity_id');
            $position = $connection->fetchOne($select, ['entity_id' => $afterCategoryId]);
            $position += 1;
        } else {
            $position = 1;
        }

        $bind = ['position' => new \Zend_Db_Expr($positionField . ' + 1')];
        $where = ['parent_id = ?' => $newParent->getId(), $positionField . ' >= ?' => $position];
        $connection->update($table, $bind, $where);

        return $position;
    }

    /**
     * Get maximum position of child categories by specific tree path
     *
     * @param string $path
     * @return int
     */
    protected function getMaxPosition($path)
    {
        $connection = $this->getConnection();
        $positionField = $connection->quoteIdentifier('position');
        $level = count(explode('/', $path));
        $bind = ['c_level' => $level, 'c_path' => $path . '/%'];
        $select = $connection->select()->from(
            $this->getTable('mst_blog_category_entity'),
            'MAX(' . $positionField . ')'
        )->where(
            $connection->quoteIdentifier('path') . ' LIKE :c_path'
        )->where($connection->quoteIdentifier('level') . ' = :c_level');

        $position = $connection->fetchOne($select, $bind);
        if (!$position) {
            $position = 0;
        }
        return $position;
    }
}