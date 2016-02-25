# Magento 2 Blog Module

## Installation

Log in to the Magento server, go to your Magento install dir and run these commands:
```
composer config repositories.mirasvit-blog vcs https://github.com/mirasvit/module-blog
composer require mirasvit/module-blog:dev-master

bin/magento setup:upgrade

rm -rf pub/static/*; rm -rf var/view_preprocessed/*;
bin/magento setup:static-content:deploy
```

## Demo
[http://blog.m2.mirasvit.com/](http://blog.m2.mirasvit.com/)

## Support
[https://mirasvit.com/](https://mirasvit.com/)