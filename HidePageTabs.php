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
$showTabsByGroupCategory = array ();
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
	// hide tab according to Group/Category definition
	showTabsByGroupCategory($mPrefixedText, $links );
 	return true;
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
	$hideEdit=true;
	if ( (0 === strpos($title, 'Template:')) || ( 0 === strpos($title, 'Form:')) ){
		$hideEdit=false;
	}
	if (array_key_exists ( "views", $links )) {
		if (array_key_exists ( "formedit", $links["views"] )) {
			if (array_key_exists ( "edit", $links["views"] )) {
				if($hideEdit){
					unset( $links["views"]["edit"] );
				}else{
					unset( $links["views"]["formedit"] );
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
function showTabsByGroupCategory($title, array &$links) {
	global $wgUser, $wgOut, $showTabsByGroupCategory;
	if($title == 'HideTest'){
		if(count($showTabsByGroupCategory)>0){
			$pageCategories=$wgOut->getCategories();
			if(count($pageCategories)>0){
				echo "<br> PAGE CATEGORIES:  <br>";
				var_dump($pageCategories);
				$userGroups=$wgUser->getGroups();
				if(count($userGroups)>0){
					echo "<br> USER GROUPS:  <br>";
					var_dump($userGroups);
					if(!in_array('sysop',$userGroups)
						&&!in_array('smwadministrator',$userGroups)
							){
						//DO nothing if admin/sysop
// 						echo "<br> USER is ADMIN/SYSOP:  <br>";
// 						return true;
// 					}else{
						$hasGroupWithRights = array();
						foreach ( $showTabsByGroupCategory as $group => $categories ) {
							if(in_array($group,$userGroups)){
								$hasGroupWithRights[]=$group;
							}
						}
						if(count($hasGroupWithRights)>0){
							$hasGroupCategoryRights = array();
							foreach ( $hasGroupWithRights as $group ) {
								foreach ( $showTabsByGroupCategory[$group] as $category => $showlist ) {
									if(in_array($category,$pageCategories)){
										$hasGroupCategoryRights = array_unique (array_merge ($hasGroupCategoryRights,$showlist));
// 										$hasGroupCategoryRights = array_merge($hasGroupCategoryRights,$showlist);
									}
								}
							}
							if(count($hasGroupCategoryRights)>0){
								echo "<br> SHOW:  <br>";
								var_dump($hasGroupCategoryRights);
								foreach ( $links as $group => $tabs ) {
									echo "<br> GROUP: $group <br>";
										switch ($group) {
											case "views" :
											case "actions" :
												foreach ( $tabs as $tab => $props ) {
													echo "<br> GROUPTAB: $tab <br>";
													var_dump($props);
// 													if (in_array( $tab, $hideTabsPageList[$title][$group])) {
// 														unset( $links[$group][$tab] );
// 													}
// 													// 							else{
// 													// 								echo "<br> UNKNOWN TAB: links[$group][$tab]<br>";
// 													// 							}
												}
												break;
										}
								}
							}else{
								echo "<br> User has no allowed Group Category Show directive<br>";
							}
						}else{
							echo "<br> User has no allowed Group <br>";
						}
					}
				}else{
					echo "<br> User has no GROUPS <br>";
				}
			}else{
				echo "<br> Page has no CATEGORIES <br>";
			}
			exit();
		}
	}
}
	