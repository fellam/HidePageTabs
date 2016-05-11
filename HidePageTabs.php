<?php
/**
* Force the hiding Tabs in pages on Vector skin
*
* @package MediaWiki
* @subpackage Extensions
*
* @author: Michele Fella - michele.fella@gmail.com
*
* @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
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

// Tabs of view to remove array
$allowGroupToCategory = array();
$hideTabsByGroup= array();
//$wgHVTFUUviewsToRemove[] = 'viewsource';
//$wgHVTFUUviewsToRemove = array( 'edit', 'history', 'read', 'talk', 'viewsource','formedit' );
//$wgHVTFUUactionsToRemove = array( 'delete', 'move', 'protect', 'watch', 'variants');
//
$hideTabsPageList = array();

// Remove 'edit' permission from anonymous users
// NOTE MIK: THis is done in LocalSettings
//$wgGroupPermissions['*']['edit'] = false;

/**
 * @param $sktemplate Title
 * @param $links
 * @return bool
 */
function hidePageTabs( SkinTemplate &$sktemplate, array &$links ) {
	global $wgUser, $wgHVTFUUviewsToRemove, $wgTitle, $hideTabsPageList;
	//retrieve article title
	$mPrefixedText=$wgTitle->getPrefixedText();
	//force tabs removal for anyone
	foreach ( $hideTabsPageList as $key => $value) {
		if( $mPrefixedText == $key ) {
			//var_dump($links);
			foreach ( $links as $k=>$v ) {
				echo "<br>view=$k<br>";
				//var_dump();
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
	return true;
	// Only remove tabs if user isn't allowed to edit pages
	
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
	
	return true;
}