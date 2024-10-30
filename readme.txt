=== IE9-Pinning ===
Contributors: ThaNerd
Donate link: http://www.thanerd.net/ie9-pinning
Tags: meta, ie9, pinning
Requires at least: 3.0
Tested up to: 3.0
Stable tag: 1.1.0

Adds meta tags handled by Internet Explorer 9's pinning feature

== Description ==

I’ve written an extremely basic plugin that handles what’s described in title : Internet Explorer 9′s new Pinning feature.

With this plugin installed and enabled, if any of your blog’s authors (or yourself) pin it to their Windows 7 Taskbar, they’ll have three new links:

* Write a post
* Moderate comments
* Upload new media

It will also display the 5 most recent posts titles for everyone.

Finally, it lets you set what color should be the back and forward buttons in IE9 after someone pins it.

= Features =
* Localized to french

== Installation ==

1. Upload `IE9-Pinning` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. There's actually only these two steps!

== Frequently Asked Questions ==

= I don't have Windows 7 nor IE9, why would I install this plugin? =

Because even if you don't benefit these features, this plugin will allow your visitors to benefit them, and THEY may use Windows 7 and Internet Explorer 9...

= I changed the toolbar buttons' color, but the changes are not reflected on my browser. What's wrong? =

The coloring feature works when you pin the website to your taskbar. If you do it afterwise, the changes won't be reflected. They seem to be cached in a file located in this folder:
C:\Users\<YOUR_USER_NAME>\AppData\Roaming\Microsoft\Internet Explorer\Quick Launch\User Pinned\TaskBar
So, the change you make won't be visible unless you unpin then repin your website. The changes will however be visible to people who pin if afterwards...

== Screenshots ==

1. Here is a preview of what you could see when right-clicking your pinned website.
2. A preview of the settings panel, which only allow previewing the back/forward buttons' color

== Changelog ==

= 1.1.0 =
* Added a very basic configuration screen
* Added ability to set the "back/forward" buttons' color

= 1.0.1 =
Fixed a bug where the "Recent posts" actually displayed only the one most recent post, 5 times in a row.

= 1.0.0 =
First (too early) Release