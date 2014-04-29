<?php
	//CONTENT-MODUL:
	if (!defined ('TYPO3_MODE')) 	die ('Access denied.');
	$tempColumns = array (
		'tx_dp_macinabanners_geotarget' => array (
			'exclude' => 0,
			'displayCond' => 'FIELD:list_type:=:macina_banners_pi1',
			'label' => 'LLL:EXT:dp_macina_banners_ext/locallang_db.php:tt_content.tx_dp_macinabanners_geotarget',
			'config' => array (
				'type' => 'check',
				'renderMode' => 'checkbox'
			),
		),
		'tx_dp_macinabanners_show_untargeted_banners' => array (
			'exclude' => 0,
			'displayCond' => 'FIELD:tx_dp_macinabanners_geotarget:REQ:TRUE',
			'label' => 'LLL:EXT:dp_macina_banners_ext/locallang_db.php:tt_content.tx_dp_macinabanners_show_untargeted_banners',
			'config' => array (
				'type' => 'check',
				'renderMode' => 'checkbox'
			),
		),
	);/**/
	//list_Type == 'macina_banners_pi1'
	t3lib_div::loadTCA('tt_content');
	t3lib_extMgm::addTCAcolumns('tt_content',$tempColumns,1);
	t3lib_extMgm::addToAllTCATypes('tt_content','--div--;LLL:EXT:dp_macina_banners_ext/locallang_db.php:tt_content.tabName;;;1-1-1'); 
	t3lib_extMgm::addToAllTCAtypes("tt_content", "tx_dp_macinabanners_geotarget" );
	t3lib_extMgm::addToAllTCAtypes("tt_content", "tx_dp_macinabanners_show_untargeted_banners" );
	t3lib_extMgm::addLLrefForTCAdescr('tt_content','EXT:dp_macina_banners_ext/locallang_csh_pages.php');
	
	
	//Banner Eintrag:
	$tempColumns = array (
		'tx_dp_macinabanners_geotargets' => array (
			'exclude' => 0,
			'label' => 'LLL:EXT:dp_macina_banners_ext/locallang_db.php:tt_content.tx_dp_macinabanners_show_untargeted_banners',
			'config' => Array (
				'type' => 'select',
				'itemsProcFunc' => 'tx_dp_macinabanners_tx_macinabanners_geotarget->main',
				'size' => 5,
				'maxitems' => 50,
			),
		),
	);
	if (TYPO3_MODE=='BE') {
		include_once(t3lib_extMgm::extPath('dp_macina_banners_ext').'class.tx_dp_macinabanners_tx_macinabanners_geotarget.php');
	}
	t3lib_div::loadTCA('tx_macinabanners_banners');
	t3lib_extMgm::addTCAcolumns('tx_macinabanners_banners',$tempColumns,1);
	t3lib_extMgm::addToAllTCATypes('tx_macinabanners_banners','--div--;LLL:EXT:dp_macina_banners_ext/locallang_db.php:tx_macinabanners_banners.tabName;;;1-1-1'); 
	t3lib_extMgm::addToAllTCAtypes("tx_macinabanners_banners", "tx_dp_macinabanners_geotargets;;;;1-1-1" );
	t3lib_extMgm::addLLrefForTCAdescr('tx_macinabanners_banners','EXT:dp_macina_banners_ext/locallang_csh_pages.php');
	
	

?>