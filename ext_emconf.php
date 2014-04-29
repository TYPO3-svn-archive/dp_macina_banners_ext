<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "dp_macina_banners_ext".
 *
 * Auto generated 28-04-2014 12:31
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Advanced Banner Geotargeting',
	'description' => 'Ermöglicht geotargeting für Banner.',
	'category' => 'fe',
	'author' => 'Stephan Rotheneder',
	'author_email' => 'sr@dialogplus.at',
	'shy' => '',
	'dependencies' => 'macina_banners,df_usergeoinfo',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => '',
	'version' => '0.0.2',
	'constraints' => array(
		'depends' => array(
			'macina_banners' => '',
			'df_usergeoinfo' => '',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:12:{s:9:"ChangeLog";s:4:"ab76";s:56:"class.tx_dp_macinabanners_tx_macinabanners_geotarget.php";s:4:"0bdf";s:33:"class.ux_tx_macinabanners_pi1.php";s:4:"6d37";s:12:"ext_icon.gif";s:4:"1bdc";s:17:"ext_localconf.php";s:4:"4988";s:14:"ext_tables.php";s:4:"09ad";s:14:"ext_tables.sql";s:4:"8ca0";s:23:"locallang_csh_pages.php";s:4:"e2cf";s:16:"locallang_db.php";s:4:"c3a2";s:10:"README.txt";s:4:"ee2d";s:19:"doc/wizard_form.dat";s:4:"2baa";s:20:"doc/wizard_form.html";s:4:"8f61";}',
	'suggests' => array(
	),
);

?>