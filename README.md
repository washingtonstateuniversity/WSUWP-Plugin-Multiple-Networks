# WSUWP Multiple Networks

[![Build Status](https://travis-ci.org/washingtonstateuniversity/WSUWP-Plugin-Multiple-Networks.svg?branch=master)](https://travis-ci.org/washingtonstateuniversity/WSUWP-Plugin-Multiple-Networks)

Provides the multi-network functionality in WordPress used at Washington State University.

## Overview

WSUWP Multiple Networks adds a basic user interface for managing multiple networks in WordPress multisite.

For best results, this plugin should be added as a must-use plugin using a process similar to WSU's [Load MU Plugins](https://github.com/washingtonstateuniversity/WSUWP-Plugin-Load-MU-Plugins/blob/master/wsuwp-load-mu-plugins.php). The plugin can be installed as a standard plugin through the dashboard, though will require manual activation on every new network for all features to work as expected.

## What this plugin provides

* Add New Network screen used to add networks to the `wp_site` table.
* All Networks screen to show existing networks.
* My Networks screen on site dashboards.
* Replacement Add New Site screen allowing the entry of arbitrary domains and paths and eliminating the need for domain mapping.
* A My Networks menu with sub-My Sites menus for each network.
* Option in Edit Site to move sites between networks.
* Ability to globally activate plugins across all networks.
* Global administrators, network administrators, and site administrators treated separately.
* Removes need for confirmation/activation links when adding new users to sites.

## Contributing

All contributions are welcome. In its current state, the plugin is over-opinionated in some places and less opinionated than it should be in others. If you'd like to help clarify that or have a question, please open an issue with your thoughts.

### Needs

These are the best places to help:

* Translation support is effectively missing.
* A new "My Networks" and "My Sites" interface via REST API would be refreshing.
* There are likely several bugs awaiting that we haven't found yet. :)
