// $Id$


-- SUMMARY --


The Recently Read module displays the history of recently read content (nodes) 
a particular user has viewed. Each authenticated user has its own history 
recorded, so this module may be useful i.e. for displaying recently viewed 
products on the e-commerce site. The history is displayed as a block and each 
content type gets its own block.

Currently, Recently Read does not track history for anonymous users.

If you need more flexibility, this module can be replaced by properly configured
Flag, Rules and Views modules. Check out the following links for more details:
* http://drupal.org/node/405754
* http://jan.tomka.name/blog/list-recently-viewed-nodes-drupal

For a full description of the module, visit the project page:
  http://drupal.org/sandbox/pgorecki/1080970

To submit bug reports and feature suggestions, or to track changes:
  http://drupal.org/project/issues/1080970


-- REQUIREMENTS --

None.


-- INSTALLATION --

* Install as usual, see http://drupal.org/node/70151 for further information.


-- CONFIGURATION --

* Customize the module settings in Administer >> Site configuration >> 
  Recently read content. On this pace you can:

  - Select which content types will should be tracked by the module

    After enabling content type tracking, go to Administer >> Site building >>
    Blocks to configure the block settings of each tracked content type.

  - Recently read list length specifies the maximum number of nodes 
    per user per content type that will be stored in a databse. Note that this
    is different from "Maximum number of links to display in the block" setting
    available in the Recently read block configutation options.


-- CONTACT --

Current maintainer:
* Przemyslaw Gorecki (pgorecki) - http://drupal.org/user/642012
