=== WP Geo - Mashup Toolkit ===
Contributors: RavanH
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=ravanhagen%40gmail%2ecom&item_name=WP%20Geo%20MAshup%20Map&item_number=0%2e1&no_shipping=0&tax=0&bn=PP%2dDonationsBF&charset=UTF%2d8&lc=us
Tags: geo, wp geo, mashup, geo mashup, google map
Requires at least: 2.8
Tested up to: 3.5.2
Stable tag: 0.6

Extends WP Geo plugin with useful tools for easy migration from the Geo Mashup plugin and other missing features in WP Geo.

== Description ==

This lightweight toolkit currently constists of 3 plugins to extend [WP Geo](http://wordpress.org/extend/plugins/wp-geo/) with missing features and facilitate easy migration from [Geo Mashup](http://wordpress.org/extend/plugins/geo-mashup/). The extentions, which can be activated seperately, are:

**Mashup Map** is an extention to allow you to continue using the Geo Mashup shortcodes `[geo_mashup_map]` and `[geo_mashup_show_on_map_link]` that are on your posts and pages already -OR- use the shortcode `[wp_geo_mashup]` where you want the map to appear. See [FAQ](http://wordpress.org/extend/plugins/wp-geo-mashup-map/faq/) for extended parameters.

**Detect Coordinates** is an extention that tries to detect coordinates in post/page content when publishing via the WP backend, Postie or during Import and converts found coordinates to WP Geo readable post meta entries. 

**Import from Geo Mashup** searches the database for Geo Mashup tables and convert the geo information to WP Geo readable post meta entries. Simply activate and deactivate this plugin again... it has done its job upon activation. NOTE: It does NOT create or remove any database tables or remove old Geo Mashup data. If there is NO Geo Mashup data found, nothing will be done at all.

WP Geo Mashup Toolkit requires:

1. the http://wordpress.org/extend/plugins/wp-geo/ plugin to be **installed**, **activated** _and_ **configured**
1. the WP Geo option `Show map on` to have a checkmark at `Pages` if you are going to use the shortcode in a page and/or `Posts` if you intend on using it in a post. 

If any of these requirements are not met, the **Mashup Map** extention will replace the shortcode with **nothing!**

== Installation ==

1. Install, activate and configure [WP Geo](http://wordpress.org/extend/plugins/wp-geo/).
1. Then install this toolkit either by downloading the zip file and uploading the content to your wp-content/plugins/ folder OR use the Plugins > Add New installation process (search for *mashup map*) from within the WP back-end.
1. Activate the tools you need for your site.

== Upgrade Notice ==

WP Geo 3.3 update compatibility for shortcodes.

== Changelog ==

= 0.6 =
* FIX: WP Geo 3.3 update compatibility for shortcodes

= 0.5 =
* qTranslate compatibility
* new shortcode parameters: category_name, cat

= 0.4 =
* NEW: Detect coordinates in post content
* NEW: Import data from Geo Mashup plugin
* Added shortcode (backward) compatibility with Geo Mashup
* More shortcode parameters

= 0.3 =
* new shortcode parameters
* WP Geo 3.2.2 compatibility

= 0.2 =
* BUGFIX

= 0.1 =
* implementation of first concept
