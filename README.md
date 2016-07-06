# Blog MX | Magento 2 Blog Module by [Mirasvit](https://mirasvit.com/)

FREE, fully featured, powerful Blog solution for your online store! Magento 2 Blog MX allows you to open a blog and engage more and more customers to your shop activities using any type of content: images, video, articles etc.

## Key Features

* SEO friendly posts and URLs
* Multi-level categories
* Ability to preview post before publication or before save changes
* RSS Feed
* Tags and Tag Cloud
* Disqus comments
* Featured image for posts
* Ability to pin post at the top
* Sharing buttons

[more information](https://mirasvit.com/magento-2-extensions/blog.html)


## Installation

Log in to the Magento server, go to your Magento install dir and run these commands:
```
composer require mirasvit/module-blog

php -f bin/magento module:enable Mirasvit_Blog
php -f bin/magento setup:upgrade

rm -rf pub/static/*; rm -rf var/view_preprocessed/*;
php -f bin/magento setup:static-content:deploy
```

## Demo
[http://blog.m2.mirasvit.com/blog/fashion/](http://blog.m2.mirasvit.com/blog/fashion/)

## Sample Data
[https://github.com/mirasvit/module-blog-sample-data](https://github.com/mirasvit/module-blog-sample-data)

## Support
[https://mirasvit.com/](https://mirasvit.com/)

# Changelog
## 1.0.7
*(2016-07-06)*

#### Fixed
* Fixed issues related with M2.1 Eav

---


## 1.0.6
*(2016-06-27)*

#### Improvements
* Added an ability to related posts with products

#### Fixed
* [#25] -- Serialization issue Magento 2.1
* [#24] -- fatal error on setup:install
* [#22] -- issue with varnish cache (related with post breadrumbs)
* [#21]
* Fixed an issue with setup:di:compile-multi-tenant

---

## 1.0.5
*(2016-05-19)*

#### Improvements
* Changed repository structure. Integrated with packagist.org
* [#18] -- integrate Facebook comments
* [#16] Split Url suffix for posts and for categories

---

## 1.0.4
*(2016-05-08)*

#### Improvements
* #16 -- added ability to set url suffix (.html)
* #16-2
* Ability to enable/disable AddThis sharing buttons in configuration
* Related Products - Backend

#### Fixed
* #15 -- fixed an issue with not seo friendly post image
* Fixed an issue with sorting Recent posts
* Removed AddThis fixed toolbar
* Fixed an issue with wrong posts in recent widget
* Fixed few small issues
* #11
* Fixed an issue with syntax errors after .phtml minification
* SeoAutolinks compatibility

---

## 1.0.3
*(2016-03-22)*

#### Fixed
* Fixed issue with menu on mobile devices

---

## 1.0.2
*(2016-03-17)*

#### Features
* Block with related posts at post view page (related by tags)

#### Fixed
* Default config
* ACL

---

## 1.0.0
*(2016-02-20)*

#### Features
* RSS Feed (whole blog and per category)

#### Improvements
* Rearrange sidebar blocks
* Search by blog
* Ability to defined date-time format
* Ability to search by blog posts
* Added Disqus

#### Fixed
* BLG2-13 
* BLG2-10 
* BLG2-8 
* BLG2-6 
* BLG2-7 
* BLG2-3 
* BLG2-5 

---


