<?php

namespace Mirasvit\Blog\Model\Post\Attribute\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Eav\Model\Entity\Attribute\Source\SourceInterface;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\User\Model\ResourceModel\User\CollectionFactory as UserCollectionFactory;

class Author extends AbstractSource implements SourceInterface, OptionSourceInterface
{
    /**
     * @var UserCollectionFactory
     */
    protected $userCollectionFactory;

    /**
     * @param UserCollectionFactory $userCollectionFactory
     */
    public function __construct(
        UserCollectionFactory $userCollectionFactory
    ) {
        $this->userCollectionFactory = $userCollectionFactory;
    }

    /**
     * Retrieve option array with empty value
     * @return string[]
     */
    public function getAllOptions()
    {
        $result = [];

        foreach ($this->getOptionArray() as $index => $value) {
            $result[] = ['value' => $index, 'label' => $value];
        }

        return $result;
    }

    /**
     * Retrieve option array
     * @return string[]
     */
    public function getOptionArray()
    {
        $result = [];
        foreach ($this->userCollectionFactory->create() as $user) {
            $result[$user->getId()] = $user->getName();
        }

        return $result;
    }
}
