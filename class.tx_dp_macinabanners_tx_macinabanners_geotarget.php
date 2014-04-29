<?php

/***************************************************************
*  Copyright notice
*
*  (c) 2014 Stephan Rotheneder (stephan.rotheneder@gmail.com)
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * Class/Function which manipulates the item-array for geotargeting items.
 *
 * @author    Stephan Rotheneder<stephan.rotheneder@gmail.com>
 */
 

class tx_dp_macinabanners_tx_macinabanners_geotarget {
	var $prefixId = 'tx_dp_macinabanners_tx_macinabanners_geotarget';		// Same as class name
	var $scriptRelPath = 'class.tx_dp_macinabanners_tx_macinabanners_geotarget.php';	// Path to this script relative to the extension dir.
	var $extKey = 'dp_macina_banners_ext';	// The extension key.
	
	function main(&$params,&$pObj) {
	
		global $TYPO3_DB, $TCA;
		
		$this->loadTS(1);
		//get upload folder
		t3lib_div::loadTCA('tx_macinabanners_banners');
		include(t3lib_extMgm::extPath('df_usergeoinfo') . 'lib/geoipregionvars.php');
		$geotarget = $GEOIP_REGION_NAME;
		$icon = '';
		$countryList = explode(',',trim($this->conf['includeCountryList']));
		foreach($geotarget as $country=>$regions) 
		{
			if(!in_array($country,$countryList))
				continue;
			$params['items'][] = array($country, $country.":*", $icon);
			foreach($regions as $regionCode => $regionName)
				$params['items'][] = array($country.":".$regionName, $country.":".$regionCode, $icon);
			
		}
	}
	function loadTS($pageUid)
	{ 
       $sysPageObj = t3lib_div::makeInstance('t3lib_pageSelect'); 
       $rootLine = $sysPageObj->getRootLine($pageUid); 
       $TSObj = t3lib_div::makeInstance('t3lib_tsparser_ext'); 
       $TSObj->tt_track = 0; 
       $TSObj->init(); 
       $TSObj->runThroughTemplates($rootLine); 
       $TSObj->generateConfig(); 
       $this->conf = $TSObj->setup['plugin.'][$this->extKey.'.']; 
	} 
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/dp_macina_banners_ext/class.tx_dp_macinabanners_tx_macinabanners_geotarget.php'])    {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/dp_macina_banners_ext/class.tx_dp_macinabanners_tx_macinabanners_geotarget.php']);
}
?>