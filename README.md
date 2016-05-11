HidePageTabs
======================

The MediaWiki HidePageTabs extension allows to:

- hide article tabs, this works despite the user groups or permission. Actions such as edit, move and so on will still be available if manually pointed in the url, but the tab in the page will not be visible.  A tipical example for using this functionality is the hiding of the tabs in the Main Page, where you dont want tabs such as edit, move or delete to be visible. 

- avoid both edit and edit with form tabs to be visible: it will hide the edit with form for Templates and Forms. For all other pages, if the edit with form will be available it will hide the edit tab. This helps when you want to drive users using forms for the editing of an article

- hide views and actions tabs if the user groups is not allowed to perfoem specific actions on the article category. This works configuring for the a specific group the list of the categories where actions should be checked and the list of possible views or actions that needs to be hidden. 
The page will be always built according to the wiki wgGroupPermissions configuration, btu tabs will be eventually removed according to the configuration rules. 

Further details and example to follow..        

For details click [here](https://www.mediawiki.org/Extension:HidePageTabs) 

=======
Please help me improving this project making a donation [here](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=UBX4YGMGGWHEN)

=======
== Dependencies ==

This extension was developed for MediaWiki 1.26.2 with at least Semantic.

=======
== Installation ==

1. Download the package. Unpack the folder inside /extensions (so that the files
   are in /extensions/HidePageTabs, rename the folder if necessary).

2. In your LocalSettings.php, add the following line to the end of the file:

   require_once("$IP/extensions/HidePageTabs/HidePageTabs.php");
