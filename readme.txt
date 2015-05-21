=== DVS API ===
Contributors: Vladimir Drizheruk <vladimir@drizheruk.com.ua>
Donate link:
Tags: api, wordpress
Requires at least: 3.0
Tested up to: 1.2.2
Stable tag: 1.2.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Provides json api for different post types and taxonomies.

== Description ==

Provides json api for:

* Posts [/api/post/[post_type].json]
* Posts with term [/api/post/[post_type]/term/[term_type].json
* Terms from different taxonomies including custom  [/api/term/[taxonomy].json]

For further information and instructions please contact me at vladimir[at]drizheruk.com.ua

== Installation ==

The quickest method for installing the importer is

1. Visit Plugins
2. Search for "dvs"
3. Find and install plugin "dvs-api:
4. Go to Settings->Permalinks, select "Post name" for the Common Settings
5. Click "Save Changes"

If you would prefer to do things manually then follow these instructions:

1. Upload the `dvs-api` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Settings->Permalinks, select "Post name" for the Common Settings
4. Click "Save Changes"

== Changelog ==

= 1.2.2 =
* removed limit for posts - retrieve all posts

= 1.2.1 =
* removed limit for posts - retrieve all posts

= 1.2.0 =
* limit 99999 posts
* url  changed : Posts with term [/api/post/[post_type]/term/[term_type].json

= 1.1.1 =
* optimization

= 1.1.0 =
* json api for different post types with taxonomy

= 1.0.0 =
* json api for different post types including custom
* json api for different taxonomies including custom
