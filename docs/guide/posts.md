# Managing Posts

All blog posts (articles) are located at **Content -> Blog MX -> All Posts** section in grid, which provides the following information:
* **ID** - ID of blog post.
* **Title** - Title of the post.
* **Status** - Current Status. Read below about blog statuses.
* **Author** - Author of blog post.
* **Published On** - Date, on which post had received status **Published** and appeared on frontend.
* **Action** - Action, which should be done. There's two possible actions:
    * **Edit** - opens edit page for this post.
    * **Delete** - removes this blog post.

Use **Filters** pane for posts filtering, and **Columns** - to add or remove columns from grid display.

## Creating New Blog Post

To create a new Blog Post go to **Content -> Blog MX -> All Posts** and press **Add Post** button. You will see blog article edit page, which is divided into two sections: [**Publish**](/guide/posts#publish) and [**Main Workspace**](/guide/posts#main).

![Blog Post Creation Page](/images/posts-create-new.png)

<a name="publish"></a>
### Publish section

This section acts as a sidebar, and contains base display options for this particular article:
* **Status** - current status of post. Possible values are:
    * **Draft** - default status. It defines status in-work, and such articles won't be displayed on frontend.
    * **Pending Review** - status of finished blog, but required for approval. For now it is merely a convenience value for filtering articles, ready for publishing.
    * **Published ** - articles with this status will be shown on frontend after saving.
* **Published on** - date, which should be displayed on article page as creation date. It defaults to date of actual creation of blog, but can be set manually.
* **Pin post at the top** - marks this post as important, and displays it at the top of category or contents page regardless of publishing date.
* **Categories** - allows to assign post to one or more categories. Read more about categories [here](/guide/categories).
* **Store Views** - currently our extension does not allow to set store-dependent visibility, so here **All Store Views** is always shown.
* **Tags** - tags, that should be associated with this article, and used for quick navigation.
    %%% note
     New tags are created on-the-fly, as you create them once - they will be available for all subsequent blog posts.
    %%%
* **Author** - defines author of this blog post. Can be any of registered backend users.
* **Featured Image** - defines image, that will be placed on the top of article, and on blog preview on category or main page.
* **Alt** - alternative text, which should be shown, if **Featured Image** for some reason is unavailable.
* **Is show on Blog Home page** - defines, whether this post should be shown only on main Blog MX main page.

<a name="main"></a>
### Main workspace section

This section contains edit workspace, which you can use to create blog. In turn, it also breaks into three tabs.

**General Information** tab is default one, where you actually work, and contains the following areas:
* **Title** - title of your blog post.
* **Editor Workspace** - can be either plain or Rich HTML (use **Show/Hide Editor** button to switch), and here you can put contents of your post.
* **Excerpt** - short summary of your post, which will be shown with **Featured Image** (if defined) on category or main page.

**Search Engine Optimization** tab contains metadata, which will be included to the generated page and used by web search crawlers. It contains the following fields:
* **Meta Title** - title of the page (if not set, then will be equal to the respective field from previous tab).
* **Meta Description** - description of this page (typically there should be short summary, like in **Excerpt** field).
* **Meta Keywords** - keywords, that should be associated with this blog.
* **URL Key** - generated automatically from title, but can be overridden. Can contain only latin letters and '-' sign.

**Related Products** actually is not a tab, but a selection widget, that can associate with blog post a set of recommended products. It is a simple, yet powerful promotional tool.

After you had completed with post contents, you can **Preview Changes**. This button, located at the top buttons pane, allows to see post as it will be displayed - without actual publishing.

To save blog post, just push **Save** button.