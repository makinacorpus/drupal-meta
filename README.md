# Meta

Yet another http://drupal.org/project/metatag clone.

Metatag module is quite huge and bloated, cause some performance troubles to
entity API over usage and will force you to install the Chaos Tools module.
For all this reasons the *Meta* module exists.

## Differences with metatag

 *  This module aims to leave the user with no form for meta tags and will
    determine dynamically data to use from data input providers (mainly based
    on fields but some others exist)

 *  It uses a simple array based mapping to build meta tags to display.

 *  It stores the tag mapping at those different levels:

     *  At field settings level

     *  At instance settings level

     *  In field data for each entity if overriden

 *  Settings and mapping are different for every tag (provider / entity)
    couples (Open Graph, HTML meta tags, etc...)

 *  It does not use any cache, caching is provided by storing the computed
    tags at entity presave time directly into the field data, already ready
    to be outputed as-is, allowing very fast display at no additional SQL
    query or cache query cost.

 *  Internally everything (or almost everythin) is written in POO for easy
    extension.

