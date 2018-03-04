=== Plugin Name ===
Contributors: (this should be a list of wordpress.org userid's)
Donate link: http://example.com/
Tags: comments, spam
Requires at least: 3.0.1
Tested up to: 3.4
Stable tag: 4.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Export and import your wp_options table using WP CLI. Opiotns are stored in a YAML file to be included in source control.

== Description ==

This plugin was built to mimic the config export/import options available in Drupal 8 (namely Drush).

By dumping the options to an external file, which is tracked via source control, command line export and import (via WP CLI) is trivial and can be part of
a continuous integration deployment process.

== Installation ==

1. Install WP CLI
2. Install WP Config Sync
3. In the command line, use `wp config-sync export` and ``wp config-sync import` to either export or import your wp_options table

== Frequently Asked Questions ==

= A question that someone might have =

An answer to that question.

= What about foo bar? =

Answer to foo bar dilemma.

== Changelog ==

= 1.0.0 =
* Initial release