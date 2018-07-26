# iCaspar Analytics

[![Build Status](https://travis-ci.org/iCaspar/ic-analytics.svg?branch=master)](https://travis-ci.org/iCaspar/ic-analytics)

### A minimalist WordPress plugin to include Google Analytics on a website.

Add the Google Analytics website tracking script to a WordPress website.

The tracking script is added to all front facing pages when the user is not logged in as an administrator.

### Requirements

* WordPress version 4.7
* PHP version >= 5.6.3
* Unit and Integration tests require PHP 7.1+

### Installation

1. Download the [ic-analytics.zip](https://caspar.green/downloads/ic-analytics/ic-analytics.zip) 
   installation file.
2. Go to plugins > Add New on your WordPress dashboard. Click "Upload Plugin", select the zip file you just downloaded.
3. Activate. Done!

### Use

Just enter your analytics tracking ID (UA-XXXXXXX-YY) in the customizer Site Identity panel, or on the **Settings > 
General** page of your dashboard and you're good to go.

### Changelog

#### 1.3.1

* Add `.gitattributes` file.

#### 1.3

* Remove settings fields from customizer. (Analytics is not display-related.)

#### 1.2

* Add Unit and Integration tests.
* Remove deprecated `IcAnalytycs::enqueueGaScript()`.
* Fix bug in conditions for GA script output.
* Add Travis CI.

#### 1.1.4

* Fix improper include.

#### 1.1.3

* Fix reference to misspelled constant.

#### 1.1.2

* Remove dev dependencies from composer.
* Fix misspelling of plugin directory constant.

#### 1.1.1

* Move script html to its own view.
* Relocate installable zip file to /dist.

#### 1.1.0

* Fix Google Webmaster Tools not being able to read tracking code for site ownership verification.

#### 1.0.2

* Security patch: escapes saved tracking ID value for rendering admin fields.

#### 1.0.1

* Add tracking ID field on the Settings > General admin page

#### 1.0.0

* Initial release.
