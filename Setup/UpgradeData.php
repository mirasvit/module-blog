<?php

namespace Mirasvit\Blog\Setup;

use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Mirasvit\Blog\Setup\InstallData\PostSetup;
use Mirasvit\Blog\Setup\InstallData\PostSetupFactory;

class UpgradeData implements UpgradeDataInterface
{
    public function __construct(PostSetupFactory $postSetupFactory, EavSetupFactory $eavSetupFactory)
    {
        $this->postSetupFactory = $postSetupFactory;
        $this->eavSetupFactory  = $eavSetupFactory;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        if (version_compare($context->getVersion(), '1.0.5') < 0) {
            /** @var PostSetup $postSetup */
            $postSetup = $this->postSetupFactory->create(['setup' => $setup]);
            foreach ($this->getAttributes() as $code => $data) {
                $postSetup->addAttribute('blog_post', $code, $data);
            }
        }

        $setup->endSetup();
    }

    /**
     * @return array
     */
    private function getAttributes()
    {
        return [
            'featured_alt'          => [
                'type'   => 'varchar',
                'label'  => 'Alt',
                'input'  => 'text',
                'global' => ScopedAttributeInterface::SCOPE_STORE,
            ],
            'featured_show_on_home' => [
                'type'    => 'int',
                'label'   => 'Is show on Blog Home page',
                'input'   => 'text',
                'global'  => ScopedAttributeInterface::SCOPE_STORE,
                'default' => 1,
            ],
        ];
    }
}
