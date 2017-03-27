=== Admin Bar Button ===
Contributors: duck__boy
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=3DPCXL86N299A
Tags: admin bar, admin, bar, jquery ui, jquery, ui, widget factory, widget, factory, plugin, button, toggle, duck__boy
Requires at least: 3.8
Tested up to: 4.5.1
Stable tag: 4.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Hide the WordPress admin bar until you need it.

== Description ==

Admin Bar Button is a plugin that will create a simple button to replace the WordPress admin bar on the front end.  Clicking the button (or hovering over it) will then show the WordPress admin bar.
When using this plugin, the full height of the page is used by your site, which is particularly handy if you have fixed headers.
Please see the [Screenshots tab](http://wordpress.org/plugins/admin-bar-button/screenshots/ "Admin Bar Button &raquo; Screenshots") to see how the Admin Bar Button looks.

After activating the plugin, you can change how the Admin Bar Button looks and works by visiting the **Settings** page (*Settings &raquo; Admin Bar Button*).
However, **no user interaction is required** by the plugin; if you wish, you can simply install and activate Admin Bar Button and it'll work right away.

This plugin has been tested with the **Twenty Sixteen**, **Twenty Fifteen**, and **Twenty Fourteen** themes that are shipped with WordPress 4.5.
Should you find a theme with which it does not work, please open a new topic on the [Support tab](https://wordpress.org/support/plugin/admin-bar-button "Admin Bar Button &raquo; Support").

== Installation ==

= If you install the plugin via your WordPress blog =
1. Click 'Install Now' underneath the plugin name
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Job done!

= If you download from http://wordpress.org/plugins/ =

1. Upload the folder `admin-bar-button` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. That's it!

== Frequently Asked Questions ==

= What options are available? =

***Admin Bar Button***

* **Button text:** > The text to display in the Admin Bar Button.  You can set this to anything you want, the button will resize appropriately.
* **Position on the screen:** > Where on the screen to position the Admin Bar Button.  You can place the button in any of the four corners.  If you choose 'Bottom left' or 'Bottom right' then the WordPress admin bar will also be shown on the bottom of the screen.
* **Button activated on:** > The actions that will activate the WordPress admin bar.  Currently you can choose between when the user clicks the button, when they hover over it, or both.

***WordPress Admin Bar***

* **Reserve space:** > Whether or not to reserve space at the top of the page for the WordPress admin bar.
* **Auto-hide the admin bar:** > Whether or not to automatically hide the WordPress admin bar.
* **Admin bar show time (milliseconds):** > The time (in milliseconds) that the WordPress admin bar will be shown for.  The minimum time is 5000 (5 seconds), and setting this option to less than that will result in the default being used.  This option is irrelevant and so ignored if **Auto-hide the admin bar** is not checked.
* **Show the hide button:** > Whether or not to include a 'Hide' button on the WordPress admin bar.  This is recommended if you've not checked **Auto-hide the admin bar**
* **Show the WordPress menu:** > Whether or not to include the WordPress menu on the WordPress admin bar, when it's shown.  Beware that you'll lose access to all of the links in the WordPress menu if it's not shown.

***Colours***

* **Background colour:** > The background colour of the Admin Bar Button and the WordPress admin bar
* **Background colour (hover):** > The background hover colour of the Admin Bar Button and the WordPress admin bar. Also changes the WordPress admin bar sub-menus background colour. Note that only the colour of buttons which are hovered will change, not the entire WordPress admin bar.
* **Text colour:** > The colour of the text for the Admin Bar Button and the WordPress admin bar.
* **Text colour (hover):** > The hover colour of the text for the Admin Bar Button and the WordPress admin bar.

***Animation***

* **Animate actions:** > Whether or not to animate the showing/hiding of the WordPress admin bar.
* **Animation duration (milliseconds):** > The time (in milliseconds) that it takes for the Admin Bar Button to slide off of the screen and be replaced by the WordPress admin bar (and vice versa).  Any positive value is acceptable, and setting '0' will disable the animation.
* **Animation direction:** > The direction in which the WordPress admin bar will enter/exit the screen.

= What are the option defaults? =

***Admin Bar Button***

* **Button text:** > Show Admin Bar
* **Position on the screen:** > Top left
* **Button activated on:** > Click and hover

***WordPress Admin Bar***

* **Reserve space:** > No
* **Auto-hide the admin bar:** > Yes
* **Admin bar show time (milliseconds):** > 5000
* **Show the hide button:** > Yes
* **Show the WordPress menu:** > Yes

***Colours***

* **Background colour:** > #23282D
* **Background colour (hover):** > #32373C
* **Text colour:** > #9EA3A8
* **Text colour (hover):** > #00B9EB

***Animation***

* **Animate actions:** > Yes
* **Animation duration (milliseconds):** > 1000
* **Animation direction:** > Slide left/right

= Can I prevent the WordPress admin bar show/hide action from being animated? =

Yes, simply click on the **Animate** tab and uncheck the **Animate action** option.

= Can I restore the default settings? =

You certainly can.  Simply visit the plugin **Settings** page (*Settings » Admin Bar Button*) scroll to the bottom and click Restore Defaults.  You'll be asked to confirm that you wish to do this, and then all of the defaults will be restored.

== Screenshots ==

1. The minimised Admin Bar Button, shown when the WordPress admin bar is hidden.
2. The WordPress admin bar, as shown here, is still available when the Admin Bar Button is activated (by clicking on or hovering over it, depending on your settings).
3. The 'Admin Bar Buttons' options from the plugin settings page.
4. The 'WordPress Admin Bar' options from the plugin settings page.
5. The 'Colours' options from the plugin settings page.
6. The 'Animation' options from the plugin settings page.

== Changelog ==

= 4.1 =
* Extended the 'Position on the screen' option to all for display of the Admin Bar Button in the 'Top middle' and 'Bottom middle' positions.
* Added a warning notice if unsaved settings are detected on the Admin Bar Button settings page.
* Various bug fixes and code optimisations.

= 4.0.1 =
* Fixed 'z-index' bug causing the Admin Bar Button to hidden on some themes.
* Fixed CSS error affecting the tabs on the Admin Bar Button settings page.

= 4.0 =
* A new **Animation** section has been created, containing all of the options required for animating the show/hide of the WordPress admin bar.  In turn, the separate animation options previously found within the **Admin Bar Button** and **WordPress Admin Bar** sections have been removed.
* Tabs on the Admin Bar Button settings page are now responsive, stacking on smaller screens.
* You'll be returned to the tab you were viewing when you save the Admin Bar Button settings.  In fact, the plugin will remember what tab you were last viewing until you close the browser window/tab.
* The contextual help has been overhauled, making it easier to read, relevant to the current version, and a great first port of call if you are experiencing any difficulty.
* Every file in the plugin has been overhauled, resulting in a more robust plugin that performs with better efficiency.
* If you choose to show it, the "Hide admin bar" button has been renamed to "Hide".
* The "Hide" button has also moved.  It can now be found to the right of the standard icons.
* Finally, the "Hide" button is now responsive, ensuring that the Admin Bar displays correctly no matter the size of your screen.

= 3.2.2 =
* Include transparency when changing the background and text colours of the Admin Bar Button and the WordPress admin bar.

= 3.2.1 =
* Add colour options for background and text (including hover) for the Admin Bar Button and the WordPress admin bar

*Please visit the [FAQ tab](http://wordpress.org/plugins/admin-bar-button/faq/ "Admin Bar Button &raquo; "FAQ") if you have questions about the latest features.*

= 3.1.1 =
* Fix issue of front end scripts/styles being included when not logged in.

= 3.1 =
* Fix an issue where space reserved by the WordPress admin bar was still being added.
* Add a new option to allow the reservation of space by the WordPress admin bar if required.

= 3.0 =
* New 'Hide' button to the Admin Bar.
* Better control over the animations to show/hide the WordPress admin bar and the Admin Bar Button.
* New menu layout on the settings page in the Admin area.

= 2.2.1 =
* Fix a z-index issue that was causing the Admin Bar Button to be hidden behind fixed headers

= 2.2 =
* New option to choose the action upon which Admin Bar Button shows the WordPress admin bar; click and hover, click, or hover.
* The Admin Bar Button can now be positioned bottom left and bottom right, as well as top left and top right; the WordPress admin bar will also be moved to the bottom if the Admin Bar Button is placed there.
* The animation of the Admin Bar Button and the Admin Bar being shown/hidden is now optional.
* Added a 'Restore Defaults' button.
* Contextual help added to the settings page.

= 2.1.1 =
* Fix error where sometimes the space originally occupied by the admin bar was still being added to the page.

= 2.1 =
* **Critical Fix** - Fix a possible JS error when a visitor to the site is not logged in.
* Creation a text domain for future foreign language support.
* Updates to the FAQ's.

= 2.0 =
* New admin menu available for setting Admin Bar Button options; now there is no need to edit any JS or PHP to get the button the way you want it.
* Minor bug fix to the adminBar jQuery UI widget.

= 1.1 =
* Minor changes to function names to avoid possible clashes.
* Minor changes to the adminBar jQuery UI widget.
* Addition of screen shots.
* Updates to the FAQ's.
* Important update to the installation instructions.

= 1.0 =
* First release on the WordPress repository.

== Upgrade Notice ==

The plugin Settings page now includes an "Animation" tab for managing animations from one place, and you are returned to the tab that you were last viewing when you save your settings.  On the front end, the "Hide" button has been moved and is now responsive.  Upgrade and checkout the readme file for full details.