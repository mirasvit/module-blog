# Change Log
## 1.0.40
*(2020-06-16)*

#### Fixed
* Issue with creating posts (Magento 2.1.* only).
* Issue with posts from other storeviews in related posts on the post page.
* Issue with displaying posts with "Published at" higher than current date.

---

## 1.0.39
*(2020-04-07)*

#### Fixed
* Issue with setting image size for widgets.

---

## 1.0.38
*(2020-03-31)*

#### Fixed
* Issue with posts URL on frontend

---

## 1.0.37
*(2020-03-30)*

#### Fixed
* Issue with importing posts URL to the Advanced SEO Sitemap

---

## 1.0.36
*(2020-03-12)*

#### Fixed
* Issue with posts per page limit

---

## 1.0.35
*(2020-02-10)*

#### Fixed
* Page builder issue on Magento CE / EE

---

## 1.0.34
*(2020-01-28)*

#### Fixed
* Issue with short content present after saving post with disabled excerpt
* Page builder issue on Magento EE
* Invalid class argument on Magento 2.3.3
* Sort of the Related Posts tab on the product pages
* Issue with tags adding

#### Improvements
* MassActions in the post listing

---

## 1.0.33
*(2019-06-18)*

#### Fixed
* Issue with fulltext search (posts list)

---

## 1.0.32
*(2019-05-08)*

#### Fixed
* Change default sort from updated_at to created_at
* Fix 147
* Fix 146

---

## 1.0.31
*(2019-04-22)*

#### Fixed
* Tags issue
* Recent posts

---

## 1.0.30
*(2019-02-12)*

#### Fixed
* Web API

---

## 1.0.29
*(2019-02-05)*

#### Improvements
* Added API for manage posts

---

## 1.0.28
*(2018-12-13)*

#### Fixed
* Issue with author url [#124]()

---

## 1.0.27
*(2018-12-03)*

#### Fixed
* Compatibility with Magento 2.3

---

## 1.0.26
*(2018-08-20)*

#### Fixed
* [#111](../../issues/111) Wrong table when trying to save a post "mage_mst_blog_post_prodct"

---

## 1.0.25
*(2018-08-17)*

#### Fixed
* Issue with saving post without tags

---

## 1.0.24
*(2018-08-13)*

#### Fixed
* Issue with compilation
* Issue with Excerpt checkbox

---

## 1.0.23
*(2018-08-10)*

#### Improvements
* Code API & UI interface for post edit page

#### Fixed
* Issue with toolbar
* [#101](../../issues/101)
* [#108](../../issues/101)
* [#103](../../issues/101)

---

## 1.0.22
*(2018-08-02)*

#### Fixed
* [#40](../../issues/40)

---

# Change log

## 1.0.21
*(2018-01-02)*

#### Fixed
* XSS vulnerability in search form

---

### 1.0.20
*(2017-09-29)*

#### Fixed
* M2.2

---

### 1.0.19
*(2017-08-09)*

#### Improvements
* Added ability to sort category
* Added ability to hide featured image on blog home page
* Added ability to set alt for featured image

#### Fixed
* Creation of root category
* JS error on posts list page

---

### 1.0.18
*(2017-07-03)*

#### Fixed
* Filter blog posts by a category
* Multistore support for "Related Posts"
* Issue when content of new post does not show after preview

---

### 1.0.17
*(2017-03-16)*

#### Fixed
* Fixed support of multiple Store View for "Recent Posts"
* Fixed localized date format for post's "Published on" field

---

### 1.0.16
*(2017-02-10)*

#### Improvements
* Added alt attribute to featured image
* Added option to hide blog menu in navigation menu
* Added ability to assign post to Store View

#### Fixed
* Fixed an issue when config does not store for Store View
* Fixed license in composer.json

---

### 1.0.15
*(2017-01-17)*

#### Improvements
* Added options to resize featured image in the widget

---

### 1.0.14
*(2017-01-11)*

#### Improvements
* Added blog_page_render event

---

### 1.0.14-beta
*(2016-12-16)*

#### Fixed
* Fixed an issue with wrong paging urls

---

### 1.0.13
*(2016-12-09)*

#### Fixed
* Fixed an issue with empty posts 

---

### 1.0.12
*(2016-12-07)*

#### Fixed
* Varnish compatibility
* Blog permissions

---

### 1.0.11
*(2016-11-01)*

#### Improvements
* Allow custom sidebar blocks

#### Fixed
* Remove obsolete permission update

---

### 1.0.9
*(2016-09-27)*

#### Fixed
* Fixed an issue with updating Page Cache after publish post

---

### 1.0.8
*(2016-08-25)*

#### Fixed
* Issue with recent posts widget
* Product Blog tab posts in ascending date order

---

### 1.0.7
*(2016-07-06)*

#### Fixed
* Fixed issues related with M2.1 Eav

---

### 1.0.6
*(2016-06-27)*

#### Improvements
* Added an ability to related posts with products

#### Fixed
* Serialization issue Magento 2.1
* Fatal error on setup:install
* Issue with varnish cache (related with post breadrumbs)
* Fixed an issue with setup:di:compile-multi-tenant

---

### 1.0.5
*(2016-05-19)*

#### Improvements
* Changed repository structure. Integrated with packagist.org
* Integrate Facebook comments
* Split Url suffix for posts and for categories

---

### 1.0.4
*(2016-05-08)*

#### Improvements
* Added ability to set url suffix (.html)
* Ability to enable/disable AddThis sharing buttons in configuration
* Related Products - Backend

#### Fixed
* Fixed an issue with not seo friendly post image
* Fixed an issue with sorting Recent posts
* Removed AddThis fixed toolbar
* Fixed an issue with wrong posts in recent widget
* Fixed few small issues
* Fixed an issue with syntax errors after .phtml minification
* SeoAutolinks compatibility

---

### 1.0.3
*(2016-03-22)*

#### Fixed
* Fixed issue with menu on mobile devices

---

### 1.0.2
*(2016-03-17)*

#### Features
* Block with related posts at post view page (related by tags)

#### Fixed
* Default config
* ACL

---

### 1.0.0
*(2016-02-20)*

#### Features
* RSS Feed (whole blog and per category)

#### Improvements
* Rearrange sidebar blocks
* Search by blog
* Ability to defined date-time format
* Ability to search by blog posts
* Added Disqus

---
