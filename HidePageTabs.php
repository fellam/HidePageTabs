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
$wgExtensionCredits ['other'] [] = array (
		'path' => __FILE__,
		'name' => 'HidePageTabs',
		'author' => array (
				'Michele Fella' 
		),
		'url' => 'https://www.mediawiki.org/wiki/User:Michele.Fella',
		'description' => 'Force the hiding of article tabs on Vector skin',
		'version' => '05-01-2016' 
);

// Hooks
$wgHooks ['SkinTemplateNavigation'] [] = 'hidePageTabs';

// Arrays declaration

// hide article tabs, this works despite the user groups or permission. Actions such as edit, move and so on will still be available if manually pointed in the url, but the tab in the page will not be visible. A tipical example for using this functionality is the hiding of the tabs in the Main Page, where you dont want tabs such as edit, move or delete to be visible.
$hideTabsPageList = array ();
// hide views and actions tabs if the user groups is not allowed to perfoem specific actions on the article category. This works configuring for the a specific group the list of the categories where actions should be checked and the list of possible views or actions that needs to be hidden.
// The page will be always built according to the wiki wgGroupPermissions configuration, btu tabs will be eventually removed according to the configuration rules.
$hideTabsByGroup = array ();
//

// Remove 'edit' permission from anonymous users
// NOTE MIK: This is configured in LocalSettings according to the owner needs
// $wgGroupPermissions['*']['edit'] = false;

/**
 *
 * @param $sktemplate Title        	
 * @param
 *        	$links
 * @return bool
 */
function hidePageTabs(SkinTemplate &$sktemplate, array &$links) {
	global $wgUser, $wgTitle, $allowGroupToCategory, $hideTabsByGroup;
	// retrieve article title
	$mPrefixedText = $wgTitle->getPrefixedText ();
	// force tabs removal for anyone
	hideTabsPageList ( $mPrefixedText, $links );
	// remove edit tab if formedit exist
	checkFormEdit ( $mPrefixedText, $links );
// 	return true;
	// Only remove tabs if user isn't allowed to edit pages
	/*
	 * if ( $wgUser->isAllowed( 'edit' ) ) {
	 * echo "ALLOWED<br>";
	 * if ( (substr( $mPrefixedText, 0, 9 ) != "Template:")
	 * and (substr( $mPrefixedText, 0, 5 ) != "Form:")
	 * and (substr( $mPrefixedText, 0, 9 ) != "Category:")
	 * and (substr( $mPrefixedText, 0, 9 ) != "Property:")
	 * and (substr( $mPrefixedText, 0, 8 ) != "Special:")){
	 * $hasFormEdit = array_key_exists('formedit',$links['views']);
	 * if ( $hasFormEdit ){
	 * unset( $links['views']['edit'] );
	 * }
	 * }else{
	 * // For Template, Forms, Categories and properties always allow only edit
	 * //var_dump($links);
	 * //exit;
	 * //unset( $links['views']['formedit'] );
	 * }
	 * return false;
	 * }else{
	 * echo "NOT ALLOWED<br>";
	 * //var_dump($links);
	 * //exit;
	 * }
	 *
	 * // Remove actions tabs
	 * foreach ( $wgHVTFUUviewsToRemove as $view ) {
	 * if ( $links['views'][$view] )
	 * unset( $links['views'][$view] );
	 * }
	 */
// 	return true;
}

/**
 *
 * @param $title Article
 *        	Title
 * @param
 *        	$links
 * @return bool
 */
function hideTabsPageList($title, array &$links) {
	global $hideTabsPageList;
	if (array_key_exists ( $title, $hideTabsPageList )) {
		foreach ( $links as $group => $tabs ) {
			if (array_key_exists ( $group, $hideTabsPageList[$title] )) {
				switch ($group) {
					case "views" :
					case "actions" :
						foreach ( $tabs as $tab => $props ) {
							if (in_array( $tab, $hideTabsPageList[$title][$group])) {
								unset( $links[$group][$tab] );
							}
							// 							else{
							// 								echo "<br> UNKNOWN TAB: links[$group][$tab]<br>";
							// 							}
						}
						break;
						// 					default:
						// 						echo "<br> UNKNOWN GROUP: links[$group]<br>";
				}
			}
		}
	}
}

/**
 *
 * @param $title Article
 *        	Title
 * @param
 *        	$links
 * @return bool
 */
function checkFormEdit($title, array &$links) {
	if(strpos($title, 'emplate') !== false){
		echo "<br> THIS IS A TEMPLATE: $title <br>";
	}
	if (array_key_exists ( "views", $links )) {
		if (array_key_exists ( "formedit", $links["views"] )) {
			if (array_key_exists ( "edit", $links["views"] )) {
				echo "<br> REMOVE EDIT <br>";
			}
		}
	}
}
	