# Installing Blog MX extension

Currently Blog MX extension can be installed only via composer. Follow these steps to install this extension on your store.

1. Backup your store's database and web directory.
1. Login to SSH console on your server and navigate to root directory of the Magento 2 store.
1. Execute the following command: ``composer require mirasvit/module-blog:*``.
1. Run command ``php -f bin/magento module:enable Mirasvit_Blog`` to enable extension.
1. Run command ``php -f bin/magento setup:upgrade`` to install the extension.
1. Run command `php -f bin/magento cache:clean` for clean the cache.
1. Deploy static view files

    `rm -rf pub/static/*; rm -rf var/view_preprocessed/*; php -f bin/magento setup:static-content:deploy`
    %%% note
    If you're using Magento 2.2.x, command use ```php -f bin/magento setup:static-content:deploy -f``` to deploy static contents.
    %%%

