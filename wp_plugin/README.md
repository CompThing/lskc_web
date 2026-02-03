# Wordpress plugin to customise Lothian Sea Kayak website
This plugin is intended to replace the custom logic prevsiously embedded with the LSKC theme.

## Customisations provided
* Registration of custom post types
* Query of orders for pool sessions

## Custom post types
The LSKC Wordpress database uses custom post types for recording trips and a few other things.
The original website managed the custom post types from within PHP logic of original LSKC theme.
For a modern theme, custom post type need to be registered by a plugin to allow then to be accessed from
a Query UI.

## Query of orders
Create a block that is similar to the Query UI for posts, but instead create a Query UI for orders.
Following example from:
https://developer.wordpress.org/block-editor/getting-started/tutorial/
However making it a subclass within our main plugin

## Background
The original LSKC Wordpress site was customised entirely via logic with a custom LSKC theme that was based
off the classic twentyten theme. The base theme is no longer supported, and a migration to a more modern
block theme is required. Block themes are edited via a UI rather than code. Custom logic needs to
be moved into a plugin.
