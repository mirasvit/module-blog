<?php

namespace Mirasvit\Blog\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

class Tag extends AbstractModel implements IdentityInterface
{
    const CACHE_TAG = 'blog_tag';

    /**
     * Get identities.
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init('Mirasvit\Blog\Model\ResourceModel\Tag');
    }

    /**
     * @param string $tag
     * @return $this
     */
    public function getOrCreate($tag)
    {
        $tag = trim($tag);
        $this->load($tag, 'tag');

        if (!$this->getId()) {
            $this->setTag($tag)
                ->save();
        }

        return $this;
    }
}
