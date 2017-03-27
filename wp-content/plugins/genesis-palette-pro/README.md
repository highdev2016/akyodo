# Genesis Design Palette Pro #
**Contributors:** norcross, jjeaton, reaktivstudios  
**Tags:** genesis, design, color scheme, css  
**Donate link:** https://genesisdesignpro.com  
**Requires at least:** 3.9  
**Tested up to:** 4.5  
**Stable tag:** 1.3.20  
**License:** GPLv2 or later  
**License URI:** http://www.gnu.org/licenses/gpl-2.0.html  

Quick and easy code-free customizations for your Genesis powered site. Requires the Genesis framework.

## Description ##
This plugin creates a new Genesis settings page that allows you to design the Genesis theme. Has settings for various colors, font stacks, sizes, borders, and more. The plugin writes a CSS file (that can be cache\'d) and loads.

NOTE: This plugin requires the Genesis Framework to function and will NOT work on other themes / frameworks.

## Installation ##
1. Upload `genesis-palette-pro` to the `/wp-content/plugins/` directory.
1. Activate the plugin through the `Plugins` menu in WordPress.
1. Navigate to the "Design Palette Pro" submenu item underneath the main "Genesis" menu item
1. Enter your license key in the "Support" tab

## Frequently Asked Questions ##
### What do I do? ###
Check the [Genesis Design Palette Pro FAQs](https://genesisdesignpro.com/faq "Genesis Design Palette Pro FAQs") for any questions

### My answer isn't there ###
Please fill out the support form inside the plugin or email help@reaktivstudios.com

## Changelog ##

### 1.3.20: 2016-06-08 ###
* New: Support for the Digital Pro Genesis Child Theme!
* New: Support for the Streamline Pro Genesis Child Theme!
* New: Support for the Interior Pro Genesis Child Theme!
* Fix: Replaced deprecated function `get_currentuserinfo` with `wp_get_current_user`
* Fix: Updated helper function for checking option key / value pairs
* Fix: Education Pro: Fixed incorrect default values
* Fix: Centric Pro: Added rgba color choices to active navigation items
* Fix: Magazine Pro: Added settings for responsive icon in navigation
* Fix: Magazine Pro: Fixed default post meta font size
* Fix: Magazine Pro: Fixed footer widget area padding target
* Fix: Modern Studio Pro: Removing text decoration underline when using Entry Content add on is active
* Fix: Modern Studio Pro: Fixed trackback link colors
* Fix: Modern Studio Pro: Set Lato font to proper native loading
* Fix: News Pro: Corrected default font weight for home top widget title
* Fix: No Sidebar Pro: Corrected closing bracket location for Entry Content add on check
* Fix: Parallax Pro: Added responsive icon color and "read more" link specificity
* Fix: The 411 Pro: Fixed incorrect default values
* Tweak: Disabled favicon field if Customizer version is being used
* Tweak: Increased fade out delay on save and error notifications
* Update: Added debug function for manually setting option
* Update: Magazine Pro: Added default values for Entry Content add-on
* Update: EDD Software Licensing update library to current 1.6.4 version

### 1.3.19: 2016-03-21 ###
* New: Support for the Atmosphere Pro Genesis Child Theme!
* New: Support for the No Sidebar Pro Genesis Child Theme!
* New: Support for the Workstation Pro Genesis Child Theme!
* Fix: Added ability to bypass preview if target is set to "none"
* Fix: eleven40 Pro: Updated incorrect default font for post meta areas
* Fix: eleven40 Pro: Updated link decorations to account for Entry Content add on overrides
* Fix: Executive Pro: Updated link decorations to account for Entry Content add on overrides
* Fix: Focus Pro: Updated target class for breadcrumb backgrounds
* Fix: Metro Pro: Updated link decorations to account for Entry Content add on overrides
* Fix: News Pro: Updated link decorations to account for Entry Content add on overrides
* Fix: Minimum Pro: Updated target arrays to account for non-semantic versions
* Update: Added non-Ajax methods for license validation and support requests
* Update: AgentPress Pro: Added styles for search listing widget
* Update: Added additional debugging functions for support ticket troubleshooting

### 1.3.18: 2016-01-13 ###
* New: Support for the AgentPress Pro Genesis Child Theme!
* Fix: eleven40 Pro: Removed `h1` and `h2` tags from CSS targets to allow for non-semantic versions
* Fix: Altitude Pro: Added additional padding and margin settings
* Fix: Altitude Pro: Background color for smaller screensizes now displays
* Fix: Author Pro: Incorrect target on "Read More" link border radius
* Fix: Trim whitespace from URLs to prevent false mismatch checks
* Fix: Removed iFrame buster if Snip.ly plugin is active
* Tweak: removed Customizer link from preview pane if admin bar is present

### 1.3.17: 2015-10-29 ###
* New: Support for the Altitude Pro Genesis Child Theme!
* New: Support for the Author Pro Genesis Child Theme!
* Fix: Education Pro: Added missing responsive icon setting
* Fix: Enterprise Pro: Fixed navigation submenu CSS borders
* Fix: Executive Pro: Fixed secondary navigation default values
* Fix: Magazine Pro: Fixed incorrect defaults and missing background for home display
* Fix: Minimum Pro: Updated `always_write` declaration
* Fix: Modern Studio Pro: Fixed navigation text-transform defaults and CSS borders
* Fix: News Pro: Fixed incorrect defaults and layout area rendering for home display
* Fix: Parallax Pro: Fixed incorrect font stack defaults for home widget areas
* Fix: Parallax Pro: Added settings and CSS reset for the smaller screens margins and padding
* Update: added more detail to license verification errors
* Update: added checks and notifications for the suhosin library
* Update: added check for `max_input_vars` and serializing if default count is higher
* Update: added object and minification cache purging on save for W3 Total Cache
* Tweak: corrected / added child theme versions

### 1.3.16: 2015-07-20 ###
* New: Support for the Centric Pro Genesis Child Theme!
* New: Support for the Generate Pro Genesis Child Theme!
* New: Support for the Modern Studio Pro Genesis Child Theme!
* New: Support for the Whitespace Pro Genesis Child Theme!
* Fix: Modified saving procedure to account for PHP limits
* Fix: Modified error checking on Ajax license verification to avoid incorrect return format
* Fix: Executive Pro: Added header navigation background color
* Fix: Magazine Pro: Added sidebar featured title defaults
* Fix: Ambiance Pro: Fixed welcome widget padding to use pixels instead of percentages
* Fix: Parallax Pro: Added `!important` to footer widget links
* Fix: Parallax Pro: Added missing text alignment default value to home page widget title
* Fix: Parallax Pro: Fixed home section label from 'left' to 'right'
* Fix: Modern Studio Pro: Updated navigation setup
* Fix: Going Green Pro: Updated sidebar CSS selectors for better specificity
* Fix: Going Green Pro: Clarified alternating comment labels
* Fix: Plugin Compatibility: Disabled "frame buster" from Social Warfare in preview window
* Update: added defaults for eNews add on into child themes
* Update: added Varnish cache purging on save

### 1.3.15: 2015-06-25 ###
* New: Support for the Wintersong Pro Genesis Child Theme!
* New: Support for the Expose Pro Genesis Child Theme!
* New: Support for the Going Green Pro Genesis Child Theme!
* New: Removed license verification requirement on local development sites
* Fix: Fixed method for retrieving theme based option defaults on child themes
* Fix: Beautiful Pro: Fixed navigation submenu borders
* Fix: Beautiful Pro: Fixed pagination settings display
* Fix: Freeform CSS add-on: CSS is now included (if present) in the export and import functions
* Fix: Freeform CSS add-on: CSS now properly clears on reset
* Tweak: Updated HelpScout API integration to ensure fallback email send
* Tweak: Abstracted functions into separate classes for future unit test inclusions
* Tweak: Updated EDD remote update function from GET to POST
* Tweak: Renamed EDD Updater class to avoid potential fatal error due to other plugins
* Update: added new helper functions for upcoming Genesis 2.2 release

### 1.3.14: 2015-05-23 ###
* Fix: Hotfix for data retrieval that used incorrect escaping on older versions of PHP

### 1.3.13: 2015-05-19 ###

* New: Support for the Ambiance Pro Genesis Child Theme!
* New: Support for the Education Pro Genesis Child Theme!
* New: Support for the Cafe Pro Genesis Child Theme!
* New: Support for the Focus Pro Genesis Child Theme!
* New: Refactored HelpScout support integration to use API first, then fallback to email. No more lost tickets!
* Fix: Function for option retrieval was not working in some instances. This has been fixed.
* Fix: Fixing the localScroll function inside the preview window.
* Fix: Added cache clearing for WP Super Cache and W3 Total Cache on save.
* Fix: News Pro: Updating OOP structure for function call.
* Tweak: Included SSL verification for license check and support API call.
* Tweak: Added low PHP memory notice.
* Tweak: Added link for displaying available add ons.
* Tweak: Fallbacks for clearing and reseting plugin data.

### 1.3.12: 2015-04-21 ###

* New: Support for the Remobile Pro Genesis Child Theme!
* New: Support for RGB colors and alpha transparency!
* Fix: Plugin activation check was using incorrect option key name, causing license issue.
* Fix: Daily Dish Pro: Fix theme color scheme defaults and missing widget title settings.
* Fix: Metro Pro: Fix link color options
* Fix: Beautiful Pro: Fix border setup for welcome widget
* Fix: Executive Pro: Fix text align issue on primary navigation color
* Fix: Lifestyle Pro: Allow for home widget title background change when using a color scheme
* Tweak: consolidated child theme checks into core plugin
* Tweak: consolidated field display functions
* Tweak: removed Yoast SEO items from site head in preview mode
* Tweak: added license data purge link to UI
* Update: confirmed proper use of `add_query_args()` and `remove_query_args()`

### 1.3.11: 2015-04-06 ###

* New: Support for the Agency Pro Genesis Child Theme!
* New: Support for the 411 Pro Genesis Child Theme!
* New: Added save icon to right side menu
* Fix: License verification was running too soon, returning empty values
* Fix: CSS compiler bug was resorting selectors incorrectly
* Fix: Lifestyle Pro: content area border settings no longer affect home page widgets
* Fix: Magazine Pro: widget title background color now excludes eNews widget
* Fix: Executive Pro: post meta background color has been added
* Update: added debug tools for support requests

### 1.3.10: 2015-03-04 ###

* New: Support for the Sixteen Nine Pro Genesis Child Theme!
* Fix: CSS writer bug when multiple media queries were used
* Fix: Additional failure checks for license activation
* Fix: Removed protection file writing for non Apache servers

### 1.3.9: 2015-02-20 ###

* New: Support for the Parallax Pro Genesis Child Theme!
* Fix: CSS bug where percent values were being written as px
* Fix: Check for eNews add-on before adding sections for widget in themes
* Fix: Dismissing add-on mismatch warning now works as expected
* Tweak: standardized functions inside child theme add ons
* Update: daily check for license status and in-dash renewal link

### 1.3.8: 2015-02-16 ###

* New: Support for the Magazine Pro Genesis Child Theme!
* Fix: Prevent incorrect URL scheme on preview (SSL / non SSL)
* Fix: Save user-entered preview URL when loading
* Fix: removed invalid warning on CSS folder check
* Tweak: added !important rule capabilities to CSS preview and generation
* Update: preparation for translation
* Update: cssTidy library to current 1.5.3 version

### 1.3.7: 2015-01-30 ###

* New: Support for the Enterprise Pro Genesis Child Theme!
* Fix: Updating nonce error on save
* Fix: News Pro: sidebar widget border fix
* Tweak: Consolidated internal function returns
* Tweak: Standardized class setup for PHP strict errors
* Tweak: Updated WP admin bar item setup
* Update: EDD updater class to current 1.6.0 version
* Update: screenfull.js library to current 2.0.0 version

### 1.3.6: 2014-12-19 ###

* Fix: CSS change for slider elements

### 1.3.5: 2014-12-05 ###

* New: Support for the Outreach Pro Genesis Child Theme!
* New: News Pro: Add Featured Posts widget entry title support.
* Fix: Patch the updater
* Fix: News Pro: Fix theme color scheme defaults.
* Fix: Modern Portfolio Pro: Fix theme color scheme defaults.

### 1.3.4: 2014-11-26 ###

* New: Support for the News Pro Genesis Child Theme!
* Tweak: Accept an array of classes for body_override on style sections.
* Fix: Multiple fixes for incorrect defaults in Modern Portfolio Pro.
* Fix: Strip BOM from API calls for license requests.
* Fix: Adjust placement of warning notices.
* Fix: Replace `$_REQUEST` with `$_GET` or `$_POST`.

### 1.3.3: 2014-11-03 ###

* New: Support for the Modern Portfolio Pro Genesis Child Theme!
* Tweak: Add new action 'gppro_after_clear'.
* Fix: Add new setting for Minimum Pro tagline margin to separate homepage use.
* Fix: Fixed a conflict with jQuery UI Tabs in the preview.
* Fix: Issue where entry background colors would also appear on Featured post widgets.
* Fix: Updated EDD Updater to the latest version.
* Fix: Targeting for header navigation items.
* Fix: Add header navigation dropdown settings for Executive Pro.
* Fix: Add header navigation link decoration settings to Executive Pro.

### 1.3.2: 2014-09-30 ###

* New: Support for the Daily Dish Pro Genesis Child Theme! Aaahhh yyyeeaaahh!
* Tweak: non-style related settings moved to separate options
* Tweak: modified save function to only store settings changed by user
* Tweak: increased timeout length for license key verification
* Tweak: added additional plugins to preview compatibility function
* Tweak: updated EDD plugin update class to current version (v1.2)
* Fix: added text-transform property to footer
* Fix: "View as logged in" not saving
* Fix: Certain CSS specificity rules now correctly accounted for
* Fix: minor admin-related CSS and JS cleanup

### 1.3.1: 2014-09-17 ###

* New: Support for the Lifestyle Pro Genesis Child Theme! Woo hoo!
* Tweak: Replace link borders with text-decoration options for Genesis. If you were using the link border options, your settings for text-decoration should be updated appropriately.
* Tweak: Remove header image support. All supported child themes have support for custom headers in WordPress core. For stock Genesis and Genesis Sample, we have added custom header support by default. It can be disabled using the `gppro_enable_header_image_support` filter and the header options can be modified with the `gppro_custom_header_args` filter. Genesis 2.1+ includes a header logo image that's 360px x 60px.
* Fix: Beautiful Pro: Issue with showing/hiding the Site Description.
* Fix: Metro Pro: Issue with Middle Widget Area and Header Nav link colors.
* Fix: Add missing sidebar and footer widget content alignment setting.
* Fix: Issue with removing values from URL fields.
* Fix: Allow PNG, ICO, and GIF files for favicon upload.

### 1.3.0: 2014-09-03 ###

* New: Improved UI. Huge performance increase on load and when scrolling. No more jerkiness!
* New: Genesis 2.1+ compatibility. The core plugin and all existing supported child themes have been updated to their latest defaults. Where possible we have provided backwards compatibility for users running older versions of Genesis or the child themes.
* New: After Entry widget area section. Enable in child themes by adding the new function to a sections filter.
* New: Support request via widget now includes Genesis theme version (parent or child) for debugging purposes.
* Metro Pro: Updated for v2.0.1.
* Metro Pro: Fix corrupted images.
* Minimum Pro: Updated for v3.0.1.
* Minimum Pro: Increase specificity for portfolio archive title weight. Increase range and set correct default for site tagline top margin.
* eleven40 Pro: Updated for v2.2.1.
* eleven40 Pro: Fix footer background color and increase range for post column footer top margin.
* Executive Pro: Updated for v3.1.1.
* Executive Pro: Change Home CTA content target to .three-fourths div.
* Executive Pro: Add missing background color setting to .entry-comment-link.
* Executive Pro: Add homepage slider content styles.
* Executive Pro: Hide secondary nav drop down settings as the secondary nav is limited to one level.
* Beautiful Pro: Updated for v1.1.
* Tweak: Remove header image settings. Use the Appearance > Header menu item instead.
* Tweak: Remove rem units from all styles by default. Possible re-enable with a filter.
* Tweak: Change font-size dropdowns to number inputs, no more limited dropdown menu.
* Tweak: Don't strip px/rem units from defaults.
* Tweak: Ensure only allowed users can see DPP admin bar menu item.
* Tweak: Favicon uploader now allows .ico, .gif and .png files.
* Tweak: Add child theme version info to header of each child theme extension.
* Tweak: Remove jQuery UI.
* Tweak: Refactor CSS builder to reduce errors.
* Tweak: Change .menu-primary and .menu-secondary selectors to .nav-primary and .nav-secondary.
* Tweak: Admin assets are now minified.
* Tweak: If key is not found for array_insert_* helpers, insert the section at the bottom.
* Fix: Increase specificity on active nav items.
* Fix: Image CSS builder now works with 'none' values.
* Fix: Add missing focus selectors to hover styles.

### 1.2.4: 2014-07-13 ###

* Fix: Minor issue with Executive Pro Home styles.

### 1.2.3: 2014-06-20 ###

* Tweak: Added ability to include cursive and handwritten font styles
* Tweak: included `home_url()` in EDD activation to reduce errors.

### 1.2.2: 2014-06-11 ###

* Fix: Fixed CSS padding issue with secondary navigation items.

### 1.2.1: 2014-06-06 ###

* Fix: Fixed a fatal error in the addon deactivation routine on some configurations.
* Fix: Included minified versions of admin assets.

### 1.2.0: 2014-06-05 ###

* New: Child theme addons are now integrated into the core plugin. You can deactivate any existing child theme addons. You can change the selected child theme from the Settings tab in DPP.
* New: Executive Pro is now supported!
* New: Beautiful Pro is now supported!
* New: Settings to make fonts italic with 'font-style'
* New: New hooks to add your own child themes: 'gppro_child_themes' and 'gppro_load_child_theme_extension_{$theme}'
* Tweak: Added Text Appearance (text-transform) setting to entry titles.
* Tweak: The front-end CSS file is now loaded with a protocol-relative URL to improve HTTPS compatibility.

### 1.1.1: 2014-05-13 ###

* New: Favicon field. Upload a custom favicon from the "General Body" settings tab.
* Tweak: Change 'gppro_before_plugin_compat' action to a filter named 'gppro_disable_plugin_compat' to allow developers to disable any of the plugin compatibility tweaks.
* Tweak: Removed filter 'gppro_stack_css_array'. Now any font stacks added with the 'gppro_font_stacks' filter are automatically available in the builder.
* Tweak: Multisite compatibility updates

### 1.1.0: 2014-04-08 ###

* New: Added full-screen mode
* New: Added CSSTidy to optimize generated CSS file. Can be disabled using the gppro_enable_css_optimization filter.
* New: Added gppro_alt_body_class filter
* New: Add plugin compatibility hooks for the preview window. Can use to unhook other plugin's functions that you don't want to run in the preview window. New actions: gppro_before_plugin_compat and gppro_after_plugin_compat.
* Tweak: Moved "Clear Settings" button to Settings tab to avoid unintentional actions
* Tweak: Add minified JS and CSS in the admin, show un-minified if SCRIPT_DEBUG is true.
* Tweak: Tooltips now use jQuery UI's Tooltip, and work in full screen mode!
* Tweak: Custom icons replaced with dashicons.
* Fix: Add check for SSL in preview window
* Fix: Heartbeat API is now disabled in the preview window.
* Fix: If the uploads folder isn't writeable, fallback to injecting the generated CSS into the head.
* Other Minor bug fixes and tweaks.

### 1.0.4: 2014-02-26 ###

* Fixed dropdown nav font size not loading properly

### 1.0.3: 2014-01-26 ###

* Fixed whitescreen error when switching to a non-Genesis theme

### 1.0.2: 2014-01-08 ###

* Changed method to check for active parent theme

### 1.0.1: 2014-01-07 ###

* Bugfixes for JS

### 1.0.0: 2014-01-04 ###

* Initial release.

## Upgrade Notice ##

### 1.3.5: 2014-12-05 ###

* *Critical Update*. 1.3.4 and 1.3.5 both include a patched version of the plugin updater. 1.3.3 included a version with a bug that may prevent future updates. If you aren't seeing updates, please go to [My Account](https://genesisdesignpro.com/my-account/) and download the latest version.

### 1.3.0: 2014-09-03 ###

* Large update. Please ensure all Design Palette Pro-related addons are up to date. For best results upgrade your Genesis child theme to the latest version if you haven't made any modifications directly to your theme.

### 1.2.0: 2014-06-05 ###

* Child theme addons are now integrated into the core plugin. To avoid issues please deactivate any child theme addons you have before upgrading.

### 1.0.0: 2014-01-04 ###

* Initial release.
