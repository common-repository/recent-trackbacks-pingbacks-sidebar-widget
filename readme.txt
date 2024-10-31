
=== Recent Trackbacks & Pingbacks Sidebar Widget ===
Contributors: Crazy Girl
Donate link: none
Tags: trackbacks, pingbacks, widget, sidebar widget, recent, last
Requires at least: 2.8
Tested up to: 3.4.1
Stable tag: 1.1

Displays the recent trackbacks and pingbacks in a customizable sidebar widget 

== Description ==

This Wordpress Widget allows you to display the recent Trackbacks and Pingbacks you received as a sidebar widget. 
So you can honor the blogs linking you with a link in your Sidebar. The Plugin is easy to install, the Widget is 
simple to use and customizable.

Yon can easily configure this Widget in the Wordpress Appearance Widget SubPanel as follows:

* add an own title to the sidebar widget
* define how many items you want to display
* add the 'rel=nofollow' attribute to your links
* define how many characters of the excerpt you want to display with the link
* exclude your own pingbacks 


Alternative to the Wordpress Appearance Widgets SubPanel you can add and configure the Recent Trackbacks & Pingbacks 
Sidebar Widget directly in your theme file (e.g. sidebar.php). For details please see the installation tab.


= Available Languages =

* German
* English


== Installation ==

1. Unzip the ZIP plugin file
2. Copy the `recent-trackbacks-pingbacks` folder into your `wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Go to the Appearance Widgets SubPanel to add the Recent Trackbacks & Pingbacks Widget to your sidebar and configure it


**PHP Code and Configuration:**

Alternative to the Wordpress Appearance Widgets SubPanel you can add and configure the Recent Trackbacks & Pingbacks 
Widget directly in your theme file (e.g. sidebar.php). In this case call the function `<?php tp_recent_trackping(); ?>` 
at the place in your sidebar you want to show it. This will display the Recent Trackbacks & Pingbacks Widget with the 
default configurations. The widget output starts with `<ul><li>` and ends with `</li></ul>`. You can put anything what and as 
you want around it.

To configure the Recent Trackbacks & Pingbacks Widget in the theme file, please add an array of settings to the function. 
Following the array with the defaults:
 
`<?php tp_recent_trackping(  array(
	'show_items' => 5, 
	'nofollow_link => 0,
	'summary_length => 100,
	'exclude_own' => 0) ); ?>` 

PHP configuration options: 

* show_items => any other **number** displays the number amount of items
* nofollow_link => **0** = no 'rel=nofollow' attribute is added to links, **1** = adds 'rel=nofollow' attribute to links
* summary_length => any **number** between 10 and 500 defines how many characters of the excerpts are displayed
* exlude_own => **0** = your own pingbacks will be included, **1** your own pingbacks will be excluded

In the array you only need to define the configurations differing to the defaults, for the other configurations 
the defaults are taken automatically. So if you only want to add the excerpts with 200 characters, your PHP 
Code would be `<?php tp_recent_trackping( array('summary_length' => 200) ); ?>`.


== Screenshots ==

1. Recent Trackbacks & Pingbacks Sidebar Widget in the Wordpress Appearance Widgets SubPanel


== Changelog ==


= 1.1 =
* Name and URL Update (German plugin description updated)

= 1.0 =
* Initial release
