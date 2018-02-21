// $Id$

== SUMMARY ==

The Recently Read module displays the history of recently read Entity
a particular user has viewed. Each authenticated user has its own history
recorded, so this module may be useful i.e. for displaying recently viewed 
products on the e-commerce site. The history is displayed as a block and each 
content type gets its own block.

== REQUIREMENTS ==

Module depends on drupal core modules Views, Entity, User.


== INSTALLATION ==

* Install as usual, see https://www.drupal.org/docs/8/extending-drupal-8/installing-drupal-8-modules for further information.

== CONFIGURATION ==

You can enable/disable recently tracking for entity in Configuration > System > Recently read config.
When module is installed it automatically creates view block for recently read articles and enable tracking for entity node and content type articles.
To create new view of recently read , just add a relationship Recently read(see view block recently read articles for reference).

== CONTACT ==
First versions of Recently read module were written by Przemyslaw Gorecki and
Terry Zhang. Recently read 8.x was written by nkoporec and zibelnik.

Current maintainer:
* Nejc Koporec(nkoporec) - https://www.drupal.org/u/nkoporec
* Janez Zibelnik(janezzibelnik) - https://www.drupal.org/u/janezzibelnik
