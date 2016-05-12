<?php
/**
* Force the hiding Tabs in pages on Vector skin
*
* @package MediaWiki
* @subpackage Extensions
*
* @author: Michele Fella - michele.fella@gmail.com
*
* @license http://www.apache.org/licenses/ General Public License 2.0 or later
*
*/

$wgExtensionCredits['other'][] = array(
 'path'           => __FILE__,
 'name'           => 'HidePageTabs',
 'author'         => array( 'Michele Fella' ),
 'url'            => 'https://www.mediawiki.org/wiki/User:Michele.Fella',
 'description'    => 'Force the hiding of article tabs on Vector skin',
 'version'        => '05-01-2016',
);

// Hooks
$wgHooks['SkinTemplateNavigation'][] = 'hidePageTabs';

// Arrays declaration

// hide article tabs, this works despite the user groups or permission. Actions such as edit, move and so on will still be available if manually pointed in the url, but the tab in the page will not be visible.  A tipical example for using this functionality is the hiding of the tabs in the Main Page, where you dont want tabs such as edit, move or delete to be visible.
$hideTabsPageList = array();
// avoid both edit and edit with form tabs to be visible: it will hide the edit with form for Templates and Forms. For all other pages, if the edit with form will be available it will hide the edit tab. This helps when you want to drive users using forms for the editing of an article
$allowGroupToCategory = array();
//hide views and actions tabs if the user groups is not allowed to perfoem specific actions on the article category. This works configuring for the a specific group the list of the categories where actions should be checked and the list of possible views or actions that needs to be hidden.
//The page will be always built according to the wiki wgGroupPermissions configuration, btu tabs will be eventually removed according to the configuration rules.
$hideTabsByGroup= array();
//

// Remove 'edit' permission from anonymous users
// NOTE MIK: This is configured in LocalSettings according to the owner needs
//$wgGroupPermissions['*']['edit'] = false;

/**
 * @param $sktemplate Title
 * @param $links
 * @return bool
 */
function hidePageTabs( SkinTemplate &$sktemplate, array &$links ) {
	global $wgUser,$wgTitle,$allowGroupToCategory,$hideTabsByGroup;
	//retrieve article title
	$mPrefixedText=$wgTitle->getPrefixedText();
	//force tabs removal for anyone
	hideTabsPageList($mPrefixedText,$links);
	return true;
	// Only remove tabs if user isn't allowed to edit pages
	/*
	if ( $wgUser->isAllowed( 'edit' ) ) {
		echo "ALLOWED<br>";
		if ( (substr( $mPrefixedText, 0, 9 ) != "Template:")
				and (substr( $mPrefixedText, 0, 5 ) != "Form:")
				and (substr( $mPrefixedText, 0, 9 ) != "Category:")
				and (substr( $mPrefixedText, 0, 9 ) != "Property:")
				and (substr( $mPrefixedText, 0, 8 ) != "Special:")){
					$hasFormEdit = array_key_exists('formedit',$links['views']);
					if ( $hasFormEdit ){
						unset( $links['views']['edit'] );
					}
		}else{
			// For Template, Forms, Categories and properties always allow only edit
			//var_dump($links);
			//exit;
			//unset( $links['views']['formedit'] );
		}
		return false;
	}else{
		echo "NOT ALLOWED<br>";
		//var_dump($links);
		//exit;
	}
	
	// Remove actions tabs
	foreach ( $wgHVTFUUviewsToRemove as $view ) {
		if ( $links['views'][$view] )
			unset( $links['views'][$view] );
	}
	*/
	return true;
}

/**
 * @param $title Article Title
 * @param $links
 * @return bool
 */
function hideTabsPageList($title,array &$links){
	global $hideTabsPageList;
	foreach ( $hideTabsPageList as $key => $value) {
		if( $title == $key ) {
			//var_dump($links);
			var_dump($links);
			foreach ( $links as $k=>$v ) {
				echo "<br>view=$k<br>";
				var_dump($v);
				/*
				view=namespaces
				array(2) { ["main"]=> array(5) { ["class"]=> string(8) "selected" ["text"]=> string(9) "Main page" ["href"]=> string(25) "/wiki/index.php/Main_Page" ["primary"]=> bool(true) ["context"]=> string(7) "subject" } ["talk"]=> array(5) { ["class"]=> string(0) "" ["text"]=> string(10) "Discussion" ["href"]=> string(30) "/wiki/index.php/Talk:Main_Page" ["primary"]=> bool(true) ["context"]=> string(4) "talk" } }
				view=views
				array(3) { ["view"]=> array(5) { ["class"]=> string(8) "selected" ["text"]=> string(4) "Read" ["href"]=> string(25) "/wiki/index.php/Main_Page" ["primary"]=> bool(true) ["redundant"]=> bool(true) } ["edit"]=> array(4) { ["class"]=> string(0) "" ["text"]=> string(4) "Edit" ["href"]=> string(43) "/wiki/index.php?title=Main_Page&action=edit" ["primary"]=> bool(true) } ["history"]=> array(3) { ["class"]=> bool(false) ["text"]=> string(12) "View history" ["href"]=> string(46) "/wiki/index.php?title=Main_Page&action=history" } }
				view=actions
				array(4) { ["delete"]=> array(3) { ["class"]=> bool(false) ["text"]=> string(6) "Delete" ["href"]=> string(45) "/wiki/index.php?title=Main_Page&action=delete" } ["move"]=> array(3) { ["class"]=> bool(false) ["text"]=> string(4) "Move" ["href"]=> string(42) "/wiki/index.php/Special:MovePage/Main_Page" } ["unprotect"]=> array(3) { ["class"]=> bool(false) ["text"]=> string(17) "Change protection" ["href"]=> string(48) "/wiki/index.php?title=Main_Page&action=unprotect" } ["unwatch"]=> array(3) { ["class"]=> bool(false) ["text"]=> string(7) "Unwatch" ["href"]=> string(99) "/wiki/index.php?title=Main_Page&action=unwatch&token=05301eb41a5729140f06498e7312066e573471d0%2B%5C" } }
				view=variants
				array(0) { }
				/*
				 if($view == "actions"){
				 unset( $links['actions'] );
				 }
				 if ( $links['views'][$view] ){
				 unset( $links['views'][$view] );
				 }
				 */
			}
			exit;
		}
	}
}