# How to upgrade extension

To upgrade the extension follow next steps:

1. Backup your store's database and web directory.
1. Login to the SSH console of your server and navigate to the root directory of the Magento 2 store.
1. Run command `composer update mirasvit/module-blog` to update the extension sources.
1. Run command `php -f bin/magento setup:upgrade` to install updates.
1. Run command `php -f bin/magento cache:clean` to clean the cache.
1. Deploy static view files

    `rm -rf pub/static/*; rm -rf var/view_preprocessed/*;
      php -f bin/magento setup:static-content:deploy`
    
    
