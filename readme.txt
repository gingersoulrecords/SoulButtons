=== SoulButtons ===
Contributors: gsr,ideag
Donate link: http://gingersoulrecords.com/
Tags: button, call to action, button shortcode, shortcode
Requires at least: 4.6
Tested up to: 4.7
Stable tag: 0.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Short description here

== Description ==

This is the long description.  No limit, and you can use Markdown (as well as in the following sections).

For backwards compatibility, if this section is missing, the full length of the short description will be used, and
Markdown parsed.

A few notes about the sections above:

*   "Contributors" is a comma separated list of wordpress.org usernames
*   "Tags" is a comma separated list of tags that apply to the plugin
*   "Requires at least" is the lowest version that the plugin will work on
*   "Tested up to" is the highest version that you've *successfully used to test the plugin*. Note that it might work on
higher versions... this is just the highest one you've verified.
*   Stable tag should indicate the Subversion "tag" of the latest stable version, or "trunk," if you use `/trunk/` for
stable.

    Note that the `readme.txt` of the stable tag is the one that is considered the defining one for the plugin, so
if the `/trunk/readme.txt` file says that the stable tag is `4.3`, then it is `/tags/4.3/readme.txt` that'll be used
for displaying information about the plugin.  In this situation, the only thing considered from the trunk `readme.txt`
is the stable tag pointer.  Thus, if you develop in trunk, you can update the trunk `readme.txt` to reflect changes in
your in-development version, without having that information incorrectly disclosed about the current stable version
that lacks those changes -- as long as the trunk's `readme.txt` points to the correct stable tag.

    If no stable tag is provided, it is assumed that trunk is stable, but you should specify "trunk" if that's where
you put the stable version, in order to eliminate any doubt.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/soulbuttons` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. Use the `Settings -> SoulButtons` screen to configure the plugin
1. Insert buttons into your posts/pages via `[soulbutton]` shortcode

== Frequently Asked Questions ==

= How do I use the shortcode? =

The same way you use other WordPress shortcodes. Just wrap your button text with `[soulbutton]` and `[/soulbutton]` tags. You can use several different attributes to customize appearance and functionality of the button. See them in the next question.

Sample usage: `[soulbutton link="http://google.com"]Go to Google[/soulbutton]`

= What attributes does `[soulbutton]` shortcode have? =

Here is the list of available attributes:
* `style` - allows to choose button appearance. Available styles currently include `solid` (default), `rounded`, `border` and `transparent`.
* `link` - where button link should point to. Defaults to `#`.
* `href` - an alias of `link`.
* `css` - custom inline CSS styles.
* `class` - to add a custom class to the button.
* `id` - to add a custom id attribute to the button.
* `color` - the color of the button (to override global setting).
* `text` - the color of the text in the button (to override global setting).
* `border` - the color of the border of the button (to override global setting).
* 'track' - should button click be recorded on Google Analytics. `true`/`false` to override global setting, custom string to set custom button name,

== Screenshots ==

== Changelog ==

= 0.1.0 =
* first version to be submitted to wordpress.org

== Upgrade Notice ==

= 0.1.0 =
Initial version
