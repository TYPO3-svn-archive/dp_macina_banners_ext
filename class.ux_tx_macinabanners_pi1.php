<?php

class ux_tx_macinabanners_pi1 extends tx_macinabanners_pi1 {
	function main($content, $conf) 
	{
		return parent::main($content, $conf);
	}
	
	function listView($content, $conf) {
		global $TYPO3_DB;
		
		$this->conf = $conf; // Setting the TypoScript passed to this function in $this->conf
		$this->conf["geoip"] = $this->cObj->data['tx_dp_macinabanners_geotarget'];
		$this->conf["showAllBanners"] = $this->cObj->data['tx_dp_macinabanners_show_untargeted_banners'];
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();	// Loading the LOCAL_LANG values
		
		
		$this->siteRelPath = $GLOBALS['TYPO3_LOADED_EXT'][$this->extKey]['siteRelPath'];
		 
		// order by
		$orderby = 'sorting';
		 
		 //passende sprache oder sprachunabhaengig
		$where .= '(sys_language_uid= ' . $GLOBALS['TSFE']->sys_language_uid . ' OR sys_language_uid = -1)';
		
		// enable fields
		$where .= $this->cObj->enableFields('tx_macinabanners_banners');
		
		// nur banner mit dem richtigen placement (left right top bottom) holen
		$allowedPlacements = t3lib_div::trimExplode(',',$conf['placement']);
		
		#echo t3lib_div::debug($allowedPlacements,'allowedPlacements');
		
		// KOMMNET-288
		/* no custom categories are recognized -> BUGFIX from http://www.typo3.net/forum/beitraege//82762/ */
		foreach ($allowedPlacements AS $key => $placement)
		{			
		   if(t3lib_div::inList("top,bottom,right,left",$placement) || (strpos($placement,'tx_macinabanners_categories') !== false))
		   {
			  // BUGFIX for KOMMNET-537: all banners of default categories AND all banners with custom categories which are not defined in TS Code (e.g. rechts2, links2)
			  $allowedPlacements[$key] = $placement;
		   }
		   else
		   {
			  // BUGFIX for KOMMNET-537: all banners with custom categories which ARE defined in TS Code (e.g. rechts_tower)
			  
			  $catWhere = ' AND description LIKE \'%'.$placement.'%\'';
			  $catRS 	= $this->pi_exec_query('tx_macinabanners_categories', 0, $catWhere, '', '', '');
			  $catRow 	= $TYPO3_DB->sql_fetch_assoc($catRS);
			  if($catRow)
				$allowedPlacements[$key] = 'tx_macinabanners_categories:'.$catRow['uid'];
		   }	
		}
		
		if (count($allowedPlacements) > 0) {
			$placementClause = '';
			foreach ($allowedPlacements AS $placement) {
				if ($placementClause != '') {
					$placementClause .= ' OR ';
				}
				$placementClause .= 'placement LIKE \'%'.$placement.'%\'';
			}
			$where .= ' AND ('.$placementClause.')';
		}
		#echo t3lib_div::debug($placementClause,'placementClause');
		
		// alle banner die die aktuelle page id nicht in  excludepages stehen haben
		$where .= "AND NOT ( excludepages regexp '[[:<:]]".$GLOBALS['TSFE']->id."[[:>:]]' )"; 

		
		//IF GEOTARGETING
		if($this->conf["geoip"] == 1)
		{
			include_once(t3lib_extMgm::extPath('df_usergeoinfo') . 'lib/geoip.inc');
			$gi = geoip_open(t3lib_extMgm::extPath('df_usergeoinfo') . 'lib/GeoLiteCity.dat', GEOIP_STANDARD);		
			$rsGeoData = geoip_record_by_addr($gi, $_SERVER['REMOTE_ADDR']);
			geoip_close($gi);	
			
			$cc = $rsGeoData->country_code;
			$rc = $rsGeoData->region;
			//echo $cc.':'.$rc;
			$where .= ' AND (find_in_set(tx_dp_macinabanners_geotargets, "'.$cc.':*")>0 OR find_in_set(tx_dp_macinabanners_geotargets, "'.$cc.':'.$rc.'")>0';
			if($this->conf["showAllBanners"])
			{
				$where .= ' OR tx_dp_macinabanners_geotargets = ""';
			}
			$where .= ')';
		}
		else
		{
			$where .= ' AND tx_dp_macinabanners_geotargets = ""';
		}
		//medialights
		$queryPerformed = true;
		//Prepare and execute listing query
		if (isset($conf['enableParameterRestriction']) && !empty($conf['enableParameterRestriction'])) {
		//show only banners that match to the selected options
			$parameters = array();
		
			//get banners list according to parameters
			$RS = $TYPO3_DB->exec_SELECTquery('uid, parameters', 'tx_macinabanners_banners', '');
			while ($row = $TYPO3_DB->sql_fetch_assoc($RS)) {
				if (!empty($row['parameters'])) {
					$lines = t3lib_div::trimExplode(chr(10), $row['parameters']);
					foreach ($lines AS $line) {
						list($parameterName, $details) = t3lib_div::trimExplode('=', $line);
						$values = t3lib_div::trimExplode(",", $details);

						foreach ($values AS $value) {
							if (isset($parameters[$parameterName][$value])) {
								$parameters[$parameterName][$value] .= ',' . $row['uid'];
							} else { $parameters[$parameterName][$value] = $row['uid']; }
						}
					}
				}
			}
			
			//get parameters
			$currentParameters = $_POST + $_GET;

			$ids = '';	
			foreach ($currentParameters AS $parameter => $value) {
				if (!empty($value) && isset($parameters[$parameter][$value])) {
					if ($ids != '') { $ids .= ','; }
					$ids .= $parameters[$parameter][$value];
				}
			}

			
			if ($ids != '') {
				$res = $TYPO3_DB->exec_SELECTquery('*', 'tx_macinabanners_banners', 'uid IN ('.$ids.')');
			} else $queryPerformed = false;

		} else {
			//show all banners
			$res = $TYPO3_DB->exec_SELECTquery('*', 'tx_macinabanners_banners', $where, '', $orderby);
		}

		// banner aussortieren
		$bannerdata = array();
		while ($queryPerformed && $row = $TYPO3_DB->sql_fetch_assoc($res)) {

			if($row['pages'] && $row['recursiv']){ // wenn pages nicht leer und rekursiv angehakt ist
				
				// liste der pageids rekursiv holen
				$pidlist = $this->pi_getPidList($row['pages'],255);
				
				$pidArray = array_unique(t3lib_div::trimExplode(',',$pidlist,1));
				#t3lib_div::debug(array($pidlist,$pidArray));
				if(in_array($GLOBALS['TSFE']->id,$pidArray)){
					$bannerdata[] = $row;
				}
				
			} else if($row['pages'] && !$row['recursiv']){
				// wenn pages nicht leer und rekursiv nicht angehakt ist
				
				$pidArray = array_unique(t3lib_div::trimExplode(',',$row['pages'],1));
				if(in_array($GLOBALS['TSFE']->id,$pidArray)){
					$bannerdata[] = $row;
				}
				
			} else {
				// wenn pages leer und rekursiv nicht angehakt ist
				
				$bannerdata[] = $row;
			}
		}

		$count = count($bannerdata);
		// use mode "random
		if ($conf['mode'] == 'random' && $count > 1) {
			$randomselectnum = rand(0, $count - 1);
			$randombanner = $bannerdata[$randomselectnum];
			unset($bannerdata);
			$bannerdata[] = $randombanner;

		} elseif ($conf['mode'] == 'random_all' && $count > 1) {
		//media lights: use mode "random_all"
			shuffle($bannerdata);
		}
		
		// get template
		$this->templateCode = $this->cObj->fileResource($this->conf['templateFile']);
		 
		// get main subpart
		$templateMarker = '###template_banners###';
		$template = array ();
		$template = $this->cObj->getSubpart($this->templateCode, $templateMarker);
		 
		// get row subpart
		$rowmarker = '###row###';
		$tablerowarray = array ();
		$tablerowarray = $this->cObj->getSubpart($template, $rowmarker);
		 
		$rowdata = '';
		
		// limit number of banners shown
		$qt = $conf['results_at_a_time'] < count($bannerdata) ? $conf['results_at_a_time'] : count($bannerdata);

		for ($i=0; $i<$qt; $i++){
			
			$row = $bannerdata[$i];
			
			// update impressionsfeld on rendering banner
			if((int)$row['impressions'] < (int)$row['threshold_impressions'] || (int)$row['threshold_impressions'] == 0 || (int)$row['threshold_impressions'] == '') { 
				$TYPO3_DB->exec_UPDATEquery(
					'tx_macinabanners_banners',
					'uid='.$TYPO3_DB->fullQuoteStr($row['uid'], 'tx_macinabanners_banners'),
					array('impressions' => ++$row['impressions'])
				);
			}
			else {
				$TYPO3_DB->exec_UPDATEquery(
					'tx_macinabanners_banners',
					'uid='.$TYPO3_DB->fullQuoteStr($row['uid'], 'tx_macinabanners_banners'),
					array('hidden' => 1, 'impressions' => ++$row['impressions'])
				);
			}
			// assign borders to array
			$styles = array ('margin-top' => $row['border_top'], 
							'margin-right' => $row['border_right'],
							'margin-bottom' => $row['border_bottom'], 
							'margin-left' => $row['border_left'] );

			switch($row['bannertype']) {
				case 0:
				
				/* 
				* Grafik per Typoscript nach belieben zu konfigurieren
				* Danke an Gernot Ploiner
				*/
				$img = $this->conf['image.'];
				$img['file'] = 'uploads/tx_macinabanners/' . $row['image'];
				$img['alttext'] = $row['alttext'];

				$this->ImageName = 'uploads/tx_macinabanners/' . $row['image'];
				array_walk_recursive($img, array($this, 'replace_field_image'));

				$this->AltText = $row['alttext'];
				array_walk_recursive($img, array($this, 'replace_field_alttext'));
				
				$img = $this->cObj->IMAGE($img);
				 
				// link image with pagelink und banneruid as getvar
				if ($row['url']) {
					# bugfix hwe (http://webdesign-forum.net/thread-229.html)
					#echo t3lib_div::debug($row['url'],'url');
					
					// KOMMNET-502 bugfix banner link problem: übernimmt target nicht.
					#$linkArray = preg_split('(/ /)', $row['url']);
					$linkArray = explode(' ', $row['url']);
					
					$wrappedSubpartArray['###bannerlink###'] = t3lib_div::trimExplode("|", $this->cObj->getTypoLink("|", $GLOBALS['TSFE']->id . " " . $linkArray[1] , array( "no_cache" => 1 , $this->prefixId . "[banneruid]" => 
					$row['uid'] ) ) );
					
					$banner = join($wrappedSubpartArray['###bannerlink###'], $img);
				} else {
					$banner = $img;
				}
				
				break;
				case 1:
				
				if ($row['url']) {
					# bugfix hwe (http://webdesign-forum.net/thread-229.html)
					$linkArray = preg_split('(/ /)', $row['url']);
					
					$clickTAG = t3lib_div::getIndpEnv('TYPO3_SITE_URL') . $this->cObj->getTypoLink_URL( $GLOBALS['TSFE']->id, array( "no_cache" => 1 , $this->prefixId . "[banneruid]" => $row['uid'] ) );
				}
				
				$banner = "\n<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0\" width=\"" . $row['flash_width'] . "\" height=\"" . $row['flash_height'] . "\">\n";
				$banner .= "<param name=\"movie\" value=\"uploads/tx_macinabanners/" . $row['swf'] . "\" />\n";
				$banner .= "<param name=\"quality\" value=\"high\" />\n";
				$banner .= "<param name=\"allowScriptAccess\" value=\"sameDomain\" />\n";
				$banner .= "<param name=\"menu\" value=\"false\" />\n";
				$banner .= "<param name=\"wmode\" value=\"transparent\" />\n";
				$banner .= "<param name=\"FlashVars\" value=\"clickTAG=" . urlencode($clickTAG) . "&amp;target=" . $linkArray[1] . "\" />\n";
				$banner .= "<embed src=\"uploads/tx_macinabanners/" . $row['swf'] . "\" FlashVars=\"" . urlencode($clickTAG) . "&amp;target=" . $linkArray[1] . "\" quality=\"high\" wmode=\"transparent\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" type=\"application/x-shockwave-flash\" width=\"" . $row['flash_width'] . "\" height=\"" . $row['flash_height'] . "\"></embed>\n";
				$banner .= "</object>\n";

				break; 

				
				//medialights: html mode
				case 2:
				$banner = $row['html'];
				break;
			}
			 
			// funktion to attach styles to wrapping cell
			$banner = $this->wrapwithstyles($banner, $styles);
			 
			// create the content by replacing the marker in the template
			$markerArray = array ();
			$markerArray['###banner###'] = $banner;
			$markerArray['###alttext###'] = $row['alttext'];

			if ($row['bannertype'] == 0)
				$markerArray['###filename###'] = $row['image'];
			elseif ($row['bannertype'] == 1)
				$markerArray['###filename###'] = $row['swf'];
			else 
				$markerArray['###filename###'] = "";

			#echo t3lib_div::debug($row['url'], 'banner url');
			$markerArray['###url###'] = $row['url'];
			$markerArray['###impressions###'] = $row['impressions'];
			$markerArray['###clicks###'] = $row['clicks'];
			$markerArray['###edit###'] = $this->pi_getEditPanel($row, 'tx_macinabanners_banners');
			 
			$rowdata .= $this->cObj->substituteMarkerArrayCached($tablerowarray, $markerArray, array (), $wrappedSubpartArray);
			#echo t3lib_div::debug($rowdata, 'rowdata');
		}
		if($rowdata)
		{
			$subpartArray = array ();
			$subpartArray['###row###'] = $rowdata;
			$content = $this->cObj->substituteMarkerArrayCached($template, array (), $subpartArray, array ());
			return $content;
		} else
		{
			return;  // keine banner
		}
	}
	
	function singleView($content, $conf) {
		return parent::singleView($content,$conf);
	}
}
?>