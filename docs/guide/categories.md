# Managing Categories

Categories can be defined at **Content -> Blog MX -> Categories**. They are organized in hierarchical structure, as shown below:

![Category Structure](/images/category-structure.png)

As you see, field **Title** displays category names with indents, where indent size is the place in hierarchy. Root element can be only one, and it shall be **Magento 2 Blog** (name can be changed, of course), and all others are its children.

Field **Status** shows, whether this category is displayed on frontend.

On frontend categories are displayed in two places:
* In Top Links Menu as items (direct children of root only).
* In right navigation sidebar.

## Creating a New Category

To create a New Category, go to **Content -> Blog MX -> Categories**, and press **Add New** button. You will see Category Edit Page, which is divided into two tabs:

* **General Information** contains all base information, needed for work:
    * **Title** - is the name of category
    * **Parent Category** - defines, which category will be parent one (can be selected only one).
    * **Status** - defines, whether this category is eligible for blog posts and display. Possible values:
        * **Disabled** - default one.
        * **Enabled** - makes category eligible for blog posts.
    * **Order** - sort order, in which categories should be shown in their respective branch. 0 is the highest order.
* **Search Engine Optimization** contains meta information, that can be used by web search crawlers:
    * **Meta Title** - title of the category (if not set, then will be equal to the respective field from previous tab).
    * **Meta Description** - description of this category.
    * **Meta Keywords** - keywords, that should be associated with this category.

To save category just push **Save** button.