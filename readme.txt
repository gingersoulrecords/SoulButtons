=== SoulButtons ===
Contributors: gingersoulrecords,ideag
Donate link: https://gingersoulrecords.com/
Tags: button, call to action, button shortcode, shortcode
Requires at least: 4.6
Tested up to: 4.8
Stable tag: 0.1.9

License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Shortcodes for simple, minimal buttons. Includes options for hover animations, icons, analytics tracking, and click events.

== Description ==

SoulButtons was designed to give WordPress developers a simple, consistent, effective approach to using buttons (and other actionable links) in their layouts.

SoulButtons was conceived by a designer who found it difficult to maintain the balance between priority (e.g. how to make more important buttons/links stand out from others), consistency (limiting button styles and their behaviors so that site visitors understand how to find the right information/conversion paths), and ease of use (so non-CSSers and non-UXers could join the party) with respect to custom-coding button styles and behaviors.

SoulButtons also makes important (yet complex) button functions available just by assigning a shortcode attribute. Got Google Analytics installed? Add the track attribute and you'll see what buttons your users are clicking on more than others in your Analytics 'events' area. Need to show an element inside a dialog/modal window when a button is clicked? Assign target and targetEvent attributes to a SoulButton and you're all set.

For videos and examples, visit https://gingersoulrecords.com/soulbuttons.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/soulbuttons` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. Use the `Settings -> SoulButtons` screen to configure the plugin
1. Insert buttons into your posts/pages via `[soulbutton]` shortcode

== Frequently Asked Questions ==

= How do I create a button? =

Use `[soulbutton]` shortcode or a button in WP Editor to generate it.

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
* `track` - should button click be recorded on Google Analytics. `true`/`false` to override global setting, custom string to set custom button name.
* `icon` - allows to insert a Dashicons / Font Awesome icon to be inserted into the button. Define using full name of the icon, i.e. `dahsicons-arrow-left` or `fa-shopping-cart`.
* `icon-position` - set the icon position within the button. `before` (default) - icon is inserted before button content. `after` - icon is inserted after button content.
* `hover` - defines additional hover effects. Space-separated list. Available effects include `icon-left`, `icon-right`, `icon-top`, `icon-bottom`.
* `align` - changes text alignment in the button. Available options - `center` (default), `left`, `right`.
* `padding` - changes button padding.
* `border-width` - changes button border-width.
* `width` - changes button minimal width.
* `scrollto` - adds a visual "Scroll To" effect for anchor links (true/false).
* `scrollto-speed` - set scrollto effect speed (default 0.5).
* `scrollto-offset` - add a vertical offset for scrollto effect (default 0).
* `offcanvas-target` - define an element (via CSS selector) to take out of DOM and animate as off-canvas item.
* `offcanvas-effect` - define which effect should be used the off-canvas target. Available effect include `fadeInFromCenter`, `slideOverFromRight` (default), `pushOverFromRight`
* `offcanvas-open` - determine if offcanvas element should initially be in open or closed state (default - false).
* `prevent-default` - allows to disable default action of the link (i.e. appending hash values to url) (default -false).

== Screenshots ==

1. SoulButtons in action on Twenty Seventeen theme
2. Shortcode builder
3. Settings page

== Changelog ==

= 0.1.6 =
* add `prevent-default` attribute (#27);
* add `offcanvas-open` attribute (#29) to allow initializing off-canvas element in open state;
* rename `target`/`target-effect` attributes to `offcanvas-target`/`offcanvas-effect` (#31);
* fix `css` attribute values not being applied correctly (#32);
* restructe the shortcode generation modal, separating options into Content and Behaviour tabs (#34);
* resizing Dashicons to 80% by default;  

= 0.1.5 =
* moved screenshots to assets
* added `target`/`target-effect` attributes
* added a Settings link in plugins list
* added shortcode builder tag to Beaver Builder WP Editor fields

= 0.1.4 =
* added a `scrollto-speed` attribute
* fix missing bracket in JS
* cleanup code, add docblock comments

= 0.1.3 =
* added a `scrollto-offset` attribute

= 0.1.2 =
* added a `scrollto` attribute

= 0.1.1 =
* updated plugin description
* fixed colorpicker bug in settings
* added `font-size:inherit` for Dashicons
* added target/target-effect attributes to the shortcode

= 0.1.0 =
* first version to be submitted to wordpress.org

== Upgrade Notice ==

= 0.1.0 =
Initial version
