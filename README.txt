=== WP Config Sync ===
Contributors: mr_pablo
Donate link: https://monzo.me/paulcanning
Tags: wp_options, options, config, sync, wp-cli, cli
Requires at least: 4.0
Requires PHP: 5.3.29
Tested up to: 4.9.4
Stable tag: 1.0
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl.html

Export and import your wp_options table using WP CLI. Opiotns are stored in a YAML file to be included in source control.

== Description ==

This plugin was built to mimic the config export/import options available in Drupal 8 (namely Drush).

By dumping the options to an external file, which is tracked via source control, command line export and import (via WP CLI) is trivial and can be part of
a continuous integration deployment process.

== Installation ==

1. Install WP CLI
2. Install WP Config Sync
3. In the command line, use `wp config-sync export` and ``wp config-sync import` to either export or import your wp_options table

== Changelog ==

= 1.0.0 =
* Initial release