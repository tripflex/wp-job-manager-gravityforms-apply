=== Apply with Gravity Forms for WP Job Manager ===

Author URI: http://astoundify.com
Plugin URI: https://github.com/Astoundify/wp-job-manager-gravityforms-apply/
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=contact@appthemer.com&item_name=Donation+for+Astoundify WP Job Manager Gravity Forms
Contributors: spencerfinnell
Tags: job, job listing, job apply, gravity forms, wp job manager
Requires at least: 3.5
Tested up to: 3.8
Stable Tag: 1.2.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Allow themes using the WP Job Manager plugin to apply via a defined Gravity Form.

== Description ==

Allow themes using the WP Job Manager plugin to apply via a defined Gravity Form.

= Where can I use this? =

Astoundify has released the first fully integrated WP Job Manager theme. Check out ["Jobify"](http://themeforest.net/item/jobify-job-board-wordpress-theme/5247604?ref=Astoundify)

== Frequently Asked Questions ==

= Nothing happens when I set the Gravity Form ID? =

It is up to the theme to respect your choice to use this plugin (as there is no way to automatically insert the form). The theme you are using must add:

`if ( class_exists( 'Astoundify_Job_Manager_Apply' ) ) :
	echo do_shortcode( '[gravityform id="' . get_option( 'job_manager_gravity_form' ) . '" title="false" ajax="true"]' );`

Please also make sure you have created a hidden field that under the "Advanced" tab has the "Allow field to be dynamically populated" field checked and with a value of `application_email`

== Installation ==

1. Install and Activate
2. Go to "Job Listings > Settings" and enter the ID of the form you would like to use.

== Changelog ==

= 1.2.0: February 4, 2014 =

* New: Forms must create a hidden field with a dynamically populdated field of "application_email". This *must* be the last field of the form.

= 1.1.2: February 1, 2014 =

* Fix: Properly return variable on filter.
* Fix: Typo fix for sending job emails.

= 1.1.1: January 26, 2014 =

* Fix: Make sure resume contact submissions are going to the correct place.
* Fix: Avoid conflict with Gravity Forms when submitting a resume.

= 1.1: January 20, 2014 =

* New: Add support for a Resume Contact form when using Resume Manager

= 1.0: August 14, 2013 =

* First official release!
